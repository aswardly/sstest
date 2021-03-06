<?php
/**
 * Emoneygw\DataMapper\Hydrator\Coupon
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Specific hydrator for Coupon domain model
 */
namespace Emoneygw\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\AbstractHydrator;
use Emoneygw\Model\Concrete\Coupon as Model;

class Coupon extends AbstractHydrator
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