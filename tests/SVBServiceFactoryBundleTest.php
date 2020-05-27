<?php

namespace SVB\ServiceFactoryBundle;

use PHPUnit\Framework\TestCase;
use SVB\ServiceFactoryBundle\DependencyInjection\ServiceFactoryContainerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SVBServiceFactoryBundleTest extends TestCase
{
    public function testGetContainerExtension()
    {
        $svbServiceFactoryBundle = new SVBServiceFactoryBundle();

        $this->assertInstanceOf(
            ServiceFactoryContainerExtension::class,
            $svbServiceFactoryBundle->getContainerExtension()
        );
    }

    public function testBuild()
    {
        $svbServiceFactoryBundle = new SVBServiceFactoryBundle();

        $containerMock = $this->createMock(ContainerBuilder::class);

        $containerMock->expects($this->once())->method('addCompilerPass')->willReturn(null);

        $svbServiceFactoryBundle->build($containerMock);
    }
}
