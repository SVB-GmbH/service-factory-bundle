<?php

namespace SVB\ServiceFactoryBundle\DependencyInjection;

use SVB\ServiceFactoryBundle\FactoryServiceInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;

class ServiceFactoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $serviceDefinitions = $container->getDefinitions();

        foreach ($serviceDefinitions as $serviceDefinition) {
            if (null === $serviceDefinition->getClass()) {
                continue;
            }

            $serviceClass = '\\' . $serviceDefinition->getClass();

            try {
                if (!is_subclass_of($serviceClass, FactoryServiceInterface::class)) {
                    continue;
                }
            } catch (ClassNotFoundError $exception) {
                continue;
            }

            $factoryClass =  '\\' . $serviceClass::getFactoryServiceId();
            try {
                $container
                    ->findDefinition($factoryClass)
                    ->addMethodCall(
                        'addService',
                        [new Reference($serviceDefinition->getClass())]
                    )
                ;
            } catch (ServiceNotFoundException $exception) {
                $factoryDefinition = new Definition($factoryClass);
                $factoryDefinition
                    ->setPrivate(true)
                    ->setAutoconfigured(true)
                    ->setAutowired(true)
                    ->addMethodCall(
                        'addService',
                        [new Reference($serviceDefinition->getClass())]
                    )
                ;
                $container->addDefinitions([
                    $serviceClass::getFactoryServiceId() => $factoryDefinition,
                ]);
            }
        }
    }
}
