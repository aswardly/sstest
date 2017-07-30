<?php
/**
 * Emoneygw\Factory\DateMapper\Hydrator\Coupon
 * 
 * @author Andi Wijaya
 * @version 1
 * Factory class for hydrator for Coupon data mapper
 */
namespace Emoneygw\Factory\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\Coupon as Hydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\NamingStrategy\ArrayMapNamingStrategy;
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;

class Coupon implements FactoryInterface
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

        $mapping = require(__DIR__.'/../../../../../config/DataMapper/NameMapping/Coupon.php');
        
        $hydrator = new Hydrator();
        
        //add DateTimeFormatter HydratorStrategy for field containing \DateTime
        $hydrator->addStrategy("startDate", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->addStrategy("coupon_start_date", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->addStrategy("expiryDate", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->addStrategy("coupon_expiry_date", new DateTimeFormatterStrategy($config['dateFormat']['storageFormat']));
        $hydrator->setNamingStrategy(new ArrayMapNamingStrategy($mapping['nameMapping']));
        
        return $hydrator;
    }
}