<?php
/**
 * Emoneygw\DataMapper\Hydrator\Product
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Specific hydrator for Product domain model
 */
namespace Emoneygw\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\AbstractHydrator;
use Emoneygw\Model\Concrete\Product as Model;

class Product extends AbstractHydrator
{
    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, $object) {
        //runtime type check
        if(false === $object instanceof Model) {
            throw new \InvalidArgumentException('Invalid model type');
        }
        
        $object = parent::hydrate($data, $object);
        //set flag on hydrated object
        $object->setLoadedFromStorage($this->_loadedFromStorage);
        
        //take snapshot
        if(true === $this->_autoSnapshotAfterHydration) {
            $object->takeSnapshot();
        }
        return $object;
    }
}