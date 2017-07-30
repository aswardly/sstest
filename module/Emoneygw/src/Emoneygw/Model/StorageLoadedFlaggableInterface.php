<?php
/**
 * Emoneygw\Model\StorageLoadedFlaggableInterface
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Interface for storage loaded flagging capability of Model and Value objects 
 * (i.e. flagging that the model/value objects is loaded from storage/data persistence layer)
 */
namespace Emoneygw\Model;

interface StorageLoadedFlaggableInterface
{
    /**
     * Set flag indicating whether value object was previously loaded from storage (data persistence layer) or not
     * Warning! modifying this flag value will determine datamapper behaviour when saving object to storage (data persistence layer),
     * only modify this value if you know what you are doing
     * 
     * @param $loaded
     */
    public function setLoadedFromStorage($loaded);
    
    /**
     * Method to determine whether value object was previously loaded from storage (data persistence layer) or not
     * 
     * @return boolean
     */
    public function isLoadedFromStorage();
}