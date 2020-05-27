<?php

namespace SVB\ServiceFactoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ServiceFactoryContainerExtension extends Extension
{
    public function getAlias()
    {
        return 'svb_service_factory_bundle';
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        // No configuration needed
    }
}
