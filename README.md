# SVB Service factory bundle
Using factories to orchestrate symfony services is usually accomplished by registering a service tag on the service and pass all services with that tag to the factory.

Since 3.4 we got [this](https://symfony.com/blog/new-in-symfony-3-4-simpler-injection-of-tagged-services) pretty little feature to ease the whole process of finding tagged services and injecting them into the factory, but it's still a bit too complicated to use this functionality. 

To further improve this, we've decided to provide the AbstractServiceFactory (Factory) and the AbstractFactoryService (Service). To tell the symfony dependency injection which services inheriting AbstractFactoryService need to be added to which factories inheriting AbstractServiceFactory, all services must implement the `getFactoryServiceId` method returning the corresponding factory FQCN.

## Installing
Just run the composer require and composer does the rest!  
```php composer.phar require svb/service-factory-bundle```

## Example service
```php
use SVB\ServiceFactoryBundle\FactoryServiceInterface;

class TestService implements FactoryServiceInterface
{
    public static function getFactoryServiceId(): string
    {
        return TestFactory::class;
    }
}
```

## Example factory
```php
use SVB\ServiceFactoryBundle\AbstractServiceFactory;
use SVB\ServiceFactoryBundle\FactoryServiceInterface;

class TestFactory extends AbstractServiceFactory
{
    /**
     * @return FactoryServiceInterface[]
     */
    public function getAllServices(): array
    {
        /**
         * $this->services contains all FactoryServiceInterface inheriting
         * services that return the TestFactory FQCN on getFactoryServiceId
         */
        return $this->services;
    }
}
```

## Continuous integration
Execute unit tests: `./phpunit --coverage-html coverage`
