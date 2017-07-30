<?php
/**
 * Emoneygw\Factory\DateMapper\OrderItem
 * 
 * @author Andi Wijaya
 * @version 1
 * Factory class for OrderItem datamapper
 */
namespace Emoneygw\Factory\DataMapper;

use Emoneygw\DataMapper\Concrete\OrderItem as Mapper;
use Emoneygw\ValueObject\Concrete\OrderItem as Model;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
        $mapping = require(__DIR__.'/../../../../config/DataMapper/NameMapping/OrderItem.php');
        
        return new Mapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'), //ask service manager to get Zend\Db\Adapter\Adapter instance
            't_order_item',
            $serviceLocator->get('Emoneygw\DataMapper\Hydrator\OrderItem'),
            new Model(),
            $mapping['nameMapping'],
            $serviceLocator->get('Emoneygw\DataMapper\Concrete\Product')
        );
    }
}