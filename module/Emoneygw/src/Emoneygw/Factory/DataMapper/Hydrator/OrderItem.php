<?php
/**
 * Emoneygw\Factory\DateMapper\Hydrator\OrderItem
 * 
 * @author Andi Wijaya
 * @version 1
 * Factory class for hydrator for Order item domain model
 */
namespace Emoneygw\Factory\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\OrderItem as Hydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\NamingStrategy\ArrayMapNamingStrategy;

class OrderItem implements FactoryInterface
{
    /**
      * Create service
      * @param ServiceLocatorInterface $serviceLocator
      *
      * @return mixed
      */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //load name mapping
        $mapping = require(__DIR__.'/../../../../../config/DataMapper/NameMapping/OrderItem.php');
        
        $hydrator = new Hydrator($serviceLocator->get('Emoneygw\DataMapper\Concrete\Product'));
        
        $hydrator->setNamingStrategy(new ArrayMapNamingStrategy($mapping['nameMapping']));
        
        return $hydrator;
    }
}