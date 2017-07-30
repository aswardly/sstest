<?php
/**
 * Emoneygw\DataMapper\Hydrator\LoadedFromStorageInterface
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Interface for  for setting and getting flag value indicating that the hydrated data (model object) is previously loaded from storage (data persistence layer) or not
 * Hydrators implementing this interface should proxy the same functionality to the hydrated model objects
 * 
 * WARNING! the flag value will determine the actions performed by datamapper when persisting the model objects to storage (data persistence layer), see datamapper class and domain model class for details
 * Only modify the flag value if you understand what you are doing
 */
namespace Emoneygw\DataMapper\Hydrator;

interface LoadedFromStorageInterface
{
    /**
     * Set loaded from storage flag value
     * @param boolean $loaded flag value
     */
    public function setLoadedFromStorage($loaded);
    
    /**
     * Get loaded from storage flag value
     * @return boolean
     */
    public function getLoadedFromStorage();
}