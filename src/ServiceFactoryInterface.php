<?php

namespace SVB\ServiceFactoryBundle;

interface ServiceFactoryInterface
{
    public function addService(FactoryServiceInterface $service): ServiceFactoryInterface;
}
