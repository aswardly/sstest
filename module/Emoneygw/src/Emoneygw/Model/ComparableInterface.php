<?php
/**
 * Emoneygw\Model\ComparableInterface
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Interface for object comparison capability of Model and Value objects by comparing itself with a snapshot of itself
 */
namespace Emoneygw\Model;

interface ComparableInterface
{
    /**
     * Take a snapshot of the field values of this domain model and store it in a property (a key value array)
     * 
     * @param mixed $object the domain model object which snapshot will be taken
     * @return void
     */
    public function takeSnapshot();
    //hint for concrete class:
    //just clone the domain model object and store it in a field (domain model object creates a clone of itself and stores it as a snapshot of itself)
    //IMPORTANT: if snapshot is taken on a domain model object which already has a snapshot inside itself, then the snapshot of that domain model will be overriden with a new snapshot (which contains another previous snapshot of itself)
    //if snapshot taking is performed repeatedly, this creates a condition of a domain model snapshot inside a domain model snapshot inside a domain model snapshot inside a domain model snapshot, and so on and so on... inside a domain model object (snapshot-ception)
    //this is bad since storing snapshot consumes memory and memory could be exhausted quickly in the case of repeated snaphot taking
    //to prevent this, always clear the previous snapshot before or after taking a new snapshot (this way, the snapshot will always contain null as the snapshot of itself)
    
    /**
     * Returns the snapshot domain model object or null if snapshot was not taken
     * @return ValueObjectInterface|null
     */
    public function getSnapshot();
    
    /**
     * Removes the snapshot domain model object
     * @return void
     */
    public function clearSnapshot();
    
    /**
     * Determines whether modification to domain model has been done
     * This is done by comparing a previously taken snapshot of the model object (a snapshot of itself in the past) with the current model object itself
     * If snapshot is empty or not found, then function should return true (just consider that domain model was modified)
     * 
     * @return boolean
     */
    public function isModified();
    //note: to get details of changes/modification = perform manual comparison of each field from current domain model object and each field of the domain model object snapshot ((use getters on both objects to get field values)
}