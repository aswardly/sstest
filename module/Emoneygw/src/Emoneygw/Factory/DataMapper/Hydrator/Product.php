<?php
/**
 * Emoneygw\Factory\DateMapper\Hydrator\Product
 * 
 * @author Andi Wijaya
 * @version 1
 * Factory class for hydrator for Product data mapper
 */
namespace Emoneygw\Factory\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\Product as Hydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\NamingStrategy\ArrayMapNamingStrategy;

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
        $mapping = require(__DIR__.'/../../../../../config/DataMapper/NameMapping/Product.php');
        
        $hydrator = new Hydrator();
        $hydrator->setNamingStrategy(new ArrayMapNamingStrategy($mapping['nameMapping']));
        
        return $hydrator;
    }
}