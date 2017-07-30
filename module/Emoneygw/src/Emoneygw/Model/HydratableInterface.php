<?php
/**
 * Emoneygw\Model\HydratableInterface
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Interface for hydration and extraction capability of Model and Value objects compatible with \Zend\Stdlib\Hydrator\ArraySerializable
 */
namespace Emoneygw\Model;

interface HydratableInterface
{
    /**
     * Method for object extraction to array
     * used by ArraySerializable Hydrator
     * 
     * @return array
     */
    public function getArrayCopy();
    
    /**
     * Method used for hydrating object from array
     * used by ArraySerializable Hydrator
     * 
     * @param array $array array used for populating object
     * @return object
     */
    public function populate($array);
}