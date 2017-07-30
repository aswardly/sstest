<?php
namespace Emoneygw\Model;
/**
 * Emoneygw\Model\IsModifiedTrait
 * 
 * @author Andi Wijaya
 * @version 1
 * Trait for determining whether modification was done on any object properties by comparing a previously taken object properties snapshot with current properties values
 */

trait IsModifiedTrait
{
    /**
     * Snapshot of the domain model object in the past
     * Defaults to null (no snapshot)
     * @var null|Emoneygw\Model\ModelInterface|Emoneygw\Model\ValueObjectInterface
     */
    protected $_snapshot = null;
    
    /**
     * Implementation of interface method
     * @see ModelInterface::takeSnapshot()
     */
    public function takeSnapshot() {
        
        //clear the previous snapshot before taking snapshot, uses less memory, but risky in case of failure during snapshot taking
        $this->clearSnapshot();
        $this->_snapshot = clone $this;
        //or clear the snapshot property of the clone(the snapshot) after taking snapshot, more memory used, but safer in case of failure during snapshot taking
        //$this->getSnapshot()->clearSnapshot(); //very small risk, it's in the same php process which is very fast anyway
        
        //init snapshot
        $this->initializeSnapshot();
    }
    
    /**
     * Implementation of interface method
     * @see ModelInterface::getSnapshot()
     */
    public function getSnapshot() {
        return $this->_snapshot;
    }
    
    /**
     * Implementation of interface method
     * @see ModelInterface::getSnapshot()
     */
    public function clearSnapshot() {
        $this->_snapshot = null;
    }
    
    /**
     * Initialize snapshot after cloning to satisfy the following rules for each property of the clone:
     * - if property value is a non object (native php data type) = php already automatically created a concrete copy in the clone(snapshot), so do nothing
     * - if property value is an object but not a domain model or value object (e.g. a \DateTime instance), replace with a concrete object by manually cloning the object from original object property
     * - if property is an object and is a domain model or valueobject and if relation with this model is just a reference/ "is-a" relationship (usually 1 to 1, which later datamapper will only take the id value when extracting), manually replace the property value with a string id value of the object (only the id value will be used for comparison when determining this model modification)
     * - if property is an object and is a domain model or valueobject (or an array of domain model or valueobject) and if relation with this model is a "has-a" relationship (can be 1 to many or 1 to 1), call takeSnapshot() method on the object
     */
    abstract protected function initializeSnapshot();
    
    //NOTE: trait implementor (domain model or value object) should implement the abstract isModified() method by itself
    //guidelines for comparison in isModified() method (for determining whether domain model was modified or not):
    //for each property to be compared, apply the following rules:
    //- if property value is a non object (native php data type) = perform comparison with the same property value of the clone (snapshot), use === for comparison operator
    //- if property value is an object but not a domain model or value object (e.g. a \DateTime instance) = perform object comparison with the same property value of the clone (snapshot), use === for comparison operator
    //- if property is an object and is a domain model or valueobject and if relation with this model is just a reference/ "is-a" relationship (usually 1 to 1), compare id value of the object with id value from clone (which should already be a native php data type)
    //- if property is an object and is a a domain model or value object (or an array of domain model or valueobject) = do not perform comparison, model datamapper has an instance of datamapper for this property object that will later perform the comparison
}