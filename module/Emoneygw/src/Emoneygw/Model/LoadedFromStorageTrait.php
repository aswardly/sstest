<?php
namespace Emoneygw\Model;

/**
 * Emoneygw\Model\LoadedFromStorageTrait
 * 
 * @author Andi Wijaya
 * @version 1
 * Trait for getting/setting information about whether a class instance was loaded from storage (data persistence layer) or not
 */

trait LoadedFromStorageTrait 
{
    /**
     * Flag indicating whether object is loaded from storage or not
     * defaults to false
     * 
     * @var boolean
     */
    protected $_isLoadedFromStorage = false;
    
    /**
     * Implementation of interface method
     * @see ModelInterface::setLoadedFromStorage()
     */
    public function setLoadedFromStorage($loaded) {
        $this->_isLoadedFromStorage = !empty($loaded); //uses !empty() instead of boolval since boolval function doesnt exist in php5.4
    }
    
    /**
     * Implementation of interface method
     * @see ModelInterface::isLoadedFromStorage()
     */
    public function isLoadedFromStorage() {
        return $this->_isLoadedFromStorage;
    }
}