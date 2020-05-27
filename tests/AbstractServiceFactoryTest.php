<?php

namespace SVB\ServiceFactoryBundle;

use PHPUnit\Framework\TestCase;

class AbstractServiceFactoryTest extends TestCase
{
    public function testAddService()
    {
        $factoryServiceMock = $this->createMock(FactoryServiceInterface::class);

        $serviceFactory = new TestServiceFactory();
        $serviceFactory->addService($factoryServiceMock);

        $this->assertEquals([$factoryServiceMock], $serviceFactory->getAllServices());
    }
}

class TestServiceFactory extends AbstractServiceFactory
{
    public function getAllServices()
    {
        return $this->services;
    }
}
