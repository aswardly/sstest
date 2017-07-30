<?php
namespace Emoneygw\Model;
/**
 * Emoneygw\Model\GetterSetterTrait
 * 
 * @author Andi Wijaya
 * @version 1
 * Trait for enforcing existence of Getter and Setter methods of a class
 * getter and setter names are assumed to be camelCased
 */

trait GetterSetterTrait
{
   /**
    * Magic method for getting this object's property by calling getter method (method name is assumed to be in camelCase)
    * throws exception if getter method does not exist (defensive behavior)
    * @param String $name Property name
    * @return mixed
    * @throws \RuntimeException
    */
    public function __get($name) {
        $methodName = 'get'.ucfirst($name);
        if(method_exists($this, $methodName))
            return $this->{$methodName}();
            
        throw new \RuntimeException('Getter method : "'.$methodName.'" does not exist');
    }
	
    /**
     * Magic method for setting this object's property by calling setter method (method name is assumed to be in camelCase)
     * throws exception if setter method does not exist (defensive behavior)
     * @param String $name Property name
     * @param mixed $name Property value
     * @throws \RuntimeException
     */
    public function __set($name, $value) {
        $methodName = 'set'.ucfirst($name);
        if(method_exists($this, $methodName)) {
            return $this->{$methodName}($value);
        }
            
        throw new \RuntimeException('Setter method : "'.$methodName.'" does not exist');
    }
}