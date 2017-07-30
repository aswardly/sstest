<?php
/**
 * Emoneygw\Factory\DateMapper\Hydrator\Order
 * 
 * @author Andi Wijaya
 * @version 1
 * Factory class for hydrator for Order data mapper
 */
namespace Emoneygw\Factory\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\Order as Hydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\NamingStrategy\ArrayMapNamingStrategy;
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;

class Order implements FactoryInterface
{
    /**
      * Create service
      * @param ServiceLocatorInterface $serviceLocator
      *
      * @return mixed
      */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //get dateformat from global config
        $config = $serviceLocator->get('Configuration');

        $mapping = require(__DIR__.'/../../../../../config/DataMapper/NameMapping/Order.php');
        
        $hydrator = new Hydrator($serviceLocator->get('Emoneygw\Datamapper\Concrete\OrderItem'),
                                 $serviceLocator->get('Emoneygw\Datamapper\Concrete\Coupon')
                                );
        
        //add DateTimeFormatter HydratorStrategy for field containing \DateTime
        $hydrator->addStrategy("createdDate", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->addStrategy("order_created_date", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->addStrategy("submittedDate", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->addStrategy("order_submitted_date", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->addStrategy("processedDate", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->addStrategy("order_processed_date", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->setNamingStrategy(new ArrayMapNamingStrategy($mapping['nameMapping']));
        
        return $hydrator;
    }
}