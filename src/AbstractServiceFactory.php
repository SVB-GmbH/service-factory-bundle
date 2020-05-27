<?php

namespace SVB\ServiceFactoryBundle;

abstract class AbstractServiceFactory implements ServiceFactoryInterface
{
    /** @var FactoryServiceInterface[] */
    protected $services;

    public function addService(FactoryServiceInterface $service): ServiceFactoryInterface
    {
        $this->services[] = $service;

        return $this;
    }
}
