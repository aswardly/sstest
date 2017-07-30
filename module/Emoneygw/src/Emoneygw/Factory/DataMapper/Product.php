<?php
/**
 * Emoneygw\Factory\DateMapper\Product
 * 
 * @author Andi Wijaya
 * @version 1
 * Factory class for Product datamapper
 */
namespace Emoneygw\Factory\DataMapper;

use Emoneygw\DataMapper\Concrete\Product as Mapper;
use Emoneygw\Model\Concrete\Product as Model;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Product implements FactoryInterface
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
        $mapping = require(__DIR__.'/../../../../config/DataMapper/NameMapping/Product.php');
        
        return new Mapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'), //ask service manager to get Zend\Db\Adapter\Adapter instance
            'M_PRODUCT',
            $serviceLocator->get('Emoneygw\DataMapper\Hydrator\Product'),
            new Model(),
            $mapping['nameMapping']
        );
    }
}