<?php
/**
 * Emoneygw\ValueObject\ValueObjectInterface
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Interface for value objects
 */
namespace Emoneygw\ValueObject;

use Emoneygw\Model\ComparableInterface;
use Emoneygw\Model\HydratableInterface;
use Emoneygw\Model\StorageLoadedFlaggableInterface;

interface ValueObjectInterface extends HydratableInterface, ComparableInterface, StorageLoadedFlaggableInterface
{
    //a value object is deemed as: hydratable, comparable, and storageloaddedflaggable
    
    /**
     * Get object detail information
     * @param $encode flag, whether the information returned should be encoded or not
     * @return mixed
     */
    public function getObjectDetail($encode = true);
}