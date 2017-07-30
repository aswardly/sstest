<?php
/**
 * Emoneygw\Factory\DateMapper\Order
 * 
 * @author Andi Wijaya
 * @version 1
 * Factory class for Order datamapper
 */
namespace Emoneygw\Factory\DataMapper;

use Emoneygw\DataMapper\Concrete\Order as Mapper;
use Emoneygw\Model\Concrete\Order as Model;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
        //load name mapping
        $mapping = require(__DIR__.'/../../../../config/DataMapper/NameMapping/Order.php');
        
        return new Mapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'), //ask service manager to get Zend\Db\Adapter\Adapter instance
            'T_ORDER',
            $serviceLocator->get('Emoneygw\DataMapper\Hydrator\Order'),
            new Model(),
            $mapping['nameMapping'],
            $serviceLocator->get('Emoneygw\DataMapper\Concrete\OrderItem'),
            $serviceLocator->get('Emoneygw\Datamapper\Concrete\Coupon')
        );
    }
}