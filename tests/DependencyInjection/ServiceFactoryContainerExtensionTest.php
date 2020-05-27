<?php

namespace SVB\ServiceFactoryBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceFactoryContainerExtensionTest extends TestCase
{
    public function testGetAlias()
    {
        $serviceFactoryContainerExtension = new ServiceFactoryContainerExtension();

        $this->assertSame(
            'svb_service_factory_bundle',
            $serviceFactoryContainerExtension->getAlias()
        );
    }

    public function testLoad()
    {
        $containerMock = $this->createMock(ContainerBuilder::class);

        $serviceFactoryContainerExtension = new ServiceFactoryContainerExtension();
        $this->assertNull(
            $serviceFactoryContainerExtension->load([], $containerMock)
        );
    }
}
