<?php

namespace SVB\ServiceFactoryBundle;

interface FactoryServiceInterface
{
    /**
     * Get the factory service id
     * Used to identify the factory service where this service should be wired to.
     */
    public static function getFactoryServiceId(): string;
}
