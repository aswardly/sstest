<?php
/**
 * Emoneygw\DataMapper\Hydrator\AbstractHydrator
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Abstract hydrator for emoneygw domain models and value objects
 * Hydrator have shared common functionalities as follows: 
 * - has flag (and setters/getters for its value) containing information about whether the hydrated domain model instance was loaded from storage (data persistence layer) or not
 * - has flag (and setters/getters for its value) containing information about whether the domain model instance snapshot should be taken automatically after hydration
 * This abstract class should enforce subclasses to automatically hydrate model instance after hydration if the flag is set, but only provides enforcement of the flag setters and getters method (a weak contract, so take note when coding the subclass)
 */
namespace Emoneygw\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\LoadedFromStorageInterface;
use Zend\Stdlib\Hydrator\ArraySerializable;

class AbstractHydrator extends ArraySerializable implements LoadedFromStorageInterface
{
    /**
     * Flag value indicating data for hydration is previously loaded from storage (persistence layer)
     * defaults to false
     * @var boolean
     */
    protected $_loadedFromStorage = false;
    
    /**
     * Flag value indicating whether the domain model instance snapshot should be taken automatically after hydration
     * defaults to false
     * @var boolean
     */
    protected $_autoSnapshotAfterHydration = false;
    
    /**
     * Get loaded from storage flag value
     * @return boolean
     */
    public function getLoadedFromStorage() {
        return $this->_loadedFromStorage;
    }
    
    /**
     * Get auto snapshot after hydration flag value
     * @return boolean
     */
    public function getAutoSnapshotAfterHydration() {
        return $this->_autoSnapshotAfterHydration;
    }
    
    /**
     * Set loaded from storage flag value
     * @param boolean $loaded flag value
     */
    public function setLoadedFromStorage($loaded) {
        $this->_loadedFromStorage = !empty($loaded);
    }
    
    /**
     * Set auto snapshot after hydration flag value
     * @param boolean $auto flag value
     */
    public function setAutoSnapshotAfterHydration($auto) {
        $this->_autoSnapshotAfterHydration = !empty($auto);
    }
}