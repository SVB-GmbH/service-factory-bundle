<?php
namespace SVB\ServiceFactoryBundle\DependencyInjection {

    use Exception;
    use PHPUnit\Framework\TestCase;
    use Psr\Log\LoggerInterface;
    use SVB\ServiceFactoryBundle\FactoryServiceInterface;
    use SVB\ServiceFactoryBundle\TestServiceFactory;
    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\DependencyInjection\Definition;
    use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
    use Symfony\Component\DependencyInjection\Reference;
    use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;

    function is_subclass_of($classA, $classB)
    {
        global $return;
        global $exception;
        if (null !== $exception) {
            throw $exception;
        }
        return isset($return) ? $return : true;
    }


    class ServiceFactoryCompilerPassTest extends TestCase
    {
        public function testProcessDoesNothingWhenClassIsEmpty()
        {
            $containerMock = $this->createMock(ContainerBuilder::class);
            $serviceDefinitionMock = $this->createMock(Definition::class);

            $containerMock->expects($this->once())->method('getDefinitions')->with()->willReturn([
                $serviceDefinitionMock,
            ]);
            $serviceDefinitionMock->expects($this->once())->method('getClass')->with()->willReturn(null);

            $serviceFactoryCompilerPass = new ServiceFactoryCompilerPass();
            $serviceFactoryCompilerPass->process($containerMock);
        }

        public function testProcessDoesNothingWhenClassIsNotSubclassOfFactoryServiceInterface()
        {
            $containerMock = $this->createMock(ContainerBuilder::class);
            $serviceDefinitionMock = $this->createMock(Definition::class);

            $containerMock->expects($this->once())->method('getDefinitions')->with()->willReturn([
                $serviceDefinitionMock,
            ]);
            $serviceDefinitionMock->expects($this->exactly(2))->method('getClass')->with()->willReturn(
                LoggerInterface::class
            );

            global $return;
            $return = false;

            $serviceFactoryCompilerPass = new ServiceFactoryCompilerPass();
            $serviceFactoryCompilerPass->process($containerMock);
        }

        public function testProcessDoesNothingWhenClassIsNotFound()
        {
            $containerMock = $this->createMock(ContainerBuilder::class);
            $serviceDefinitionMock = $this->createMock(Definition::class);

            $containerMock->expects($this->once())->method('getDefinitions')->with()->willReturn([
                $serviceDefinitionMock,
            ]);
            $serviceDefinitionMock->expects($this->exactly(2))->method('getClass')->with()->willReturn(
                LoggerInterface::class
            );

            global $exception;
            $exception = new ClassNotFoundError('class not found!', new Exception('previous'));

            $serviceFactoryCompilerPass = new ServiceFactoryCompilerPass();
            $serviceFactoryCompilerPass->process($containerMock);
        }

        public function testProcessAddsMethodCallToExistingFactoryDefinition()
        {
            $containerMock = $this->createMock(ContainerBuilder::class);
            $serviceDefinitionMock = $this->createMock(Definition::class);
            $factoryDefinitionMock = $this->createMock(Definition::class);

            $containerMock->expects($this->once())->method('getDefinitions')->with()->willReturn([
                $serviceDefinitionMock,
            ]);
            $serviceDefinitionMock->expects($this->exactly(3))->method('getClass')->with()->willReturn(
                TestFactoryService::class
            );
            $containerMock
                ->expects($this->once())
                ->method('findDefinition')
                ->with('\\test1')
                ->willReturn($factoryDefinitionMock)
            ;
            $factoryDefinitionMock
                ->expects($this->once())
                ->method('addMethodCall')
                ->with()
                ->willReturn(
                    'addService',
                    [new Reference(TestFactoryService::class)]
                )
            ;

            $serviceFactoryCompilerPass = new ServiceFactoryCompilerPass();
            $serviceFactoryCompilerPass->process($containerMock);
        }

        public function testProcessAddsMethodCallToNewFactoryDefinitionAndAddsItToContainerBuilder()
        {
            $containerMock = $this->createMock(ContainerBuilder::class);
            $serviceDefinitionMock = $this->createMock(Definition::class);

            $containerMock->expects($this->once())->method('getDefinitions')->with()->willReturn([
                $serviceDefinitionMock,
            ]);
            $serviceDefinitionMock->expects($this->exactly(3))->method('getClass')->with()->willReturn(
                TestFactoryService::class
            );
            $containerMock
                ->expects($this->once())
                ->method('findDefinition')
                ->with('\\test1')
                ->willThrowException(new ServiceNotFoundException('test'))
            ;
            $factoryDefinition = new Definition('\\test1');
            $factoryDefinition
                ->setPrivate(true)
                ->setAutoconfigured(true)
                ->setAutowired(true)
                ->addMethodCall(
                    'addService',
                    [new Reference(TestFactoryService::class)]
                )
            ;
            $containerMock
                ->expects($this->once())
                ->method('addDefinitions')
                ->with([
                    'test1' => $factoryDefinition
                ])
                ->willReturn(null)
            ;

            $serviceFactoryCompilerPass = new ServiceFactoryCompilerPass();
            $serviceFactoryCompilerPass->process($containerMock);
        }
    }

    class TestFactoryService implements FactoryServiceInterface
    {
        public static function getFactoryServiceId(): string
        {
            return 'test1';
        }
    }
}
