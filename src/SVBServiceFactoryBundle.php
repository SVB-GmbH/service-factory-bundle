<?php

namespace SVB\ServiceFactoryBundle;

use SVB\ServiceFactoryBundle\DependencyInjection\ServiceFactoryCompilerPass;
use SVB\ServiceFactoryBundle\DependencyInjection\ServiceFactoryContainerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SVBServiceFactoryBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new ServiceFactoryContainerExtension();
        }

        return $this->extension;
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            new ServiceFactoryCompilerPass()
        );
    }


}
