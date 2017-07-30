<?php
/**
 * Emoneygw\Factory\DateMapper\Coupon
 * 
 * @author Andi Wijaya
 * @version 1
 * Factory class for Coupon datamapper
 */
namespace Emoneygw\Factory\DataMapper;

use Emoneygw\DataMapper\Concrete\Coupon as Mapper;
use Emoneygw\Model\Concrete\Coupon as Model;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
        //load name mapping
        $mapping = require(__DIR__.'/../../../../config/DataMapper/NameMapping/Coupon.php');
        
        return new Mapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'), //ask service manager to get Zend\Db\Adapter\Adapter instance
            'm_coupon',
            $serviceLocator->get('Emoneygw\DataMapper\Hydrator\Coupon'),
            new Model(),
            $mapping['nameMapping']
        );
    }
}