<?php
namespace Emoneygw\Model\Concrete;

use Emoneygw\Model\GetterSetterTrait;
use Emoneygw\Model\LoadedFromStorageTrait;
use Emoneygw\Model\IsModifiedTrait;
use Emoneygw\Model\ModelInterface;

/**
 * Emoneygw\Model\Concrete\Coupon
 * 
 * @author Andi Wijaya
 * @version 1
 * Domain model for coupon
 */

class Coupon implements ModelInterface
{
    use GetterSetterTrait;
    use LoadedFromStorageTrait;
    use IsModifiedTrait;
    
    /**
     * @const STATUS_VALID coupon is still valid to use
     */
    const STATUS_VALID = 'V';
	
    /**
     * @const STATUS_INVALID coupon is invalid to use
     */
    const STATUS_INVALID = 'I';
    
    /**
     * array of status labels
     * 
     * @var array
     * @static
     */
    private static $_statusLabels = array(self::STATUS_VALID => 'Valid',
                                            self::STATUS_INVALID => 'Invalid'
                                           );
										   
    /**
     * @const TYPE_VALUE discount is a numeric value
     */
    const TYPE_VALUE = 'V';
	
    /**
     * @const TYPE_PERCENTAGE discount is a percentage value
     */
    const TYPE_PERCENTAGE = 'P';
    
    /**
     * array of status labels
     * 
     * @var array
     * @static
     */
    private static $_typeLabels = array(self::TYPE_VALUE => 'Value',
                                          self::TYPE_PERCENTAGE => 'Percentage'
                                         );
	
    /**
     * coupon id
     * @var string
     */
    private $id;
    
    /**
     * coupon value
     * @var float
     */
    private $value;
    
    /**
     * coupon type
     * @var string
     */
    private $type;
    
    /**
     * coupon quantity
     * @var integer
     */
    private $quantity;
    
    /**
     * product status
     * @var string
     */
    private $status;

    /**
     * startDate
     * @var \DateTime
     */
    private $startDate;
    
    /**
     * expiryDate
     * @var \DateTime
     */
    private $expiryDate;
	
    /**
     * Get id
     * @return string
     */ 
    public function getId() {
        return $this->id;
    }
    
    /**
     * get value
     * @return float
     */
    public function getValue() {
        return $this->value;
    }
    
    /**
     * get type
     * @return string
     */
    public function getType() {
        return $this->type;
    }
	
    /**
     * get status
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * get quantity
     * @return integer
     */
    public function getQuantity() {
        return $this->quantity;
    }
    
    /**
     * Get startDate
     * @return \DateTime
     */
    public function getStartDate() {
        return $this->startDate;
    }
    
    /**
     * Get expiryDate
     * @return \DateTime
     */
    public function getExpiryDate() {
        return $this->expiryDate;
    }

    /**
     * Get label for a certain status
     * @param $status
     * @return string
     */
    public function getStatusLabel($status) {
        if(in_array($status, array_keys(self::$_statusLabels))) {
            return self::$_statusLabels[$status];
        }
        return null;
    }
    
    /**
     * Get label for a certain type
     * @param $type
     * @return string
     */
    public function getTypeLabel($type) {
        if(in_array($type, array_keys(self::$_typeLabels))) {
            return self::$_typeLabels[$type];
        }
        return null;
    }
    
    //===================================================================================================================
    // Setter methods, note that validation performed are only on data format, not business logic
    
    /**
     * Set Id
     * @param mixed $id value to set
     * @throws \InvalidArgumentException
     */
    public function setId($id) {
        if(empty($id)) {
            throw new \InvalidArgumentException('Id cannot be empty');
        }
        $this->id = $id;
    }
    
    /**
     * Set quantity
     * @param integer $quantity value to set
     */
    public function setQuantity($quantity) {
        $this->quantity = intval($quantity);
    }
    
    /**
     * Set value
     * @param float $value value to set
     */
    public function setValue($value) {
        $this->value = floatval($value);
    }
   
    /**
     * Set type
     * @param string $type value to set
     */
    public function setType($type) {
        if(false === in_array($type, array_keys(self::$_typeLabels))) {
            throw new \InvalidArgumentException("Invalid setting of type value");
        }
        $this->type = $type;
    }
    
    /**
     * Set status
     * @param string $status value to set
     */
    public function setStatus($status) {
        if(false === in_array($status, array_keys(self::$_statusLabels))) {
            throw new \InvalidArgumentException("Invalid setting of status value");
        }
        $this->status = $status;
    }
	
    /**
     * Set startDate
     * @param \DateTime $start value to set
     */
    public function setStartDate(\DateTime $start) {
        $this->startDate = $start;
    }
    
    /**
     * Set expiryDate
     * @param \DateTime $expiry value to set
     */
    public function setExpiryDate(\DateTime $expiry) {
        $this->expiryDate = $expiry;
    }
    
    /**
     * @inheritDoc
     * 
     */
    public function getObjectDetail($encode = true) {
        $tmp = new \stdClass();
        $tmp->id = $this->id;
        $tmp->quantity = $this->quantity;
        $tmp->value = $this->value;
        $tmp->type = $this->getTypeLabel($this->type);
        $tmp->status = $this->getStatusLabel($this->status);
        //NOTE: Date formats are hardcoded for now
        $tmp->startDate = ($this->startDate instanceof \DateTime)? $this->startDate->format('Y-m-d H:i:s') : $this->startDate;
        $tmp->expiryDate = ($this->expiryDate instanceof \DateTime)? $this->expiryDate->format('Y-m-d H:i:s') : $this->expiryDate;
        
        if(false === $encode) {
            return $tmp;
        }
        else {    
            return \Zend\Json\Json::encode($tmp);
        }
    }
    
    //Hydrator methods
    //===================================================================================================================
    /**
     * {@inheritDoc}
     */
    public function populate($array) {
        $this->id = $array['id'];
        $this->value = $array['value'];
        $this->type = $array['type'];
        $this->quantity = $array['quantity'];
        $this->status = $array['status'];
        $this->startDate = $array['startDate'];
        $this->expiryDate = $array['expiryDate'];
        
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getArrayCopy() {
        //do not extract statistics, this will be performed by statistics hydrator
        $array = array();
        $array['id'] = $this->id;
        $array['value'] = $this->value;
        $array['type'] = $this->type;
        $array['quantity'] = $this->quantity;
        $array['status'] = $this->status;
        $array['startDate'] = $this->startDate;
        $array['expiryDate'] = $this->expiryDate;
		
        return $array;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isModified() {
        if(is_null($this->_snapshot)) {
            //no snapshot found = can't compare = can't tell whether this object was modified or not, but return true so datamapper will save record anyway (err on the side of caution)
            //update: current use case is that snapshot in model object will only be initialized when the object was loaded from database (in datamapper code), and never when object was created manually from other code,
            //in this case, it's safe to assume that no snapshot in the model object equals to no modification was performed
            return false;
        }
        //manually compare all of the field values of the snapshot with current field values of the domain model object (tedious task)
        if($this->id != $this->_snapshot->id) {
            return true;
        }
        if($this->value != $this->_snapshot->value) {
            return true;
        }
        if($this->quantity != $this->_snapshot->quantity) {
            return true;
        }
        if($this->status != $this->_snapshot->status) {
            return true;
        }
	if($this->startDate != $this->_snapshot->startDate) {
            return true;
        }
	if($this->expiryDate != $this->_snapshot->expiryDate) {
            return true;
        }
        
        //all object properties are identical with snapshot, object was not modified, return false
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    private function initializeSnapshot() {
	//special treatment for objects, since php cloning does not create a deep copy by default
        if($this->startDate instanceof \DateTime) {
            $this->_snapshot->startDate = clone $this->startDate;
        }
        if($this->expiryDate instanceof \DateTime) {
            $this->_snapshot->expiryDate = clone $this->expiryDate;
        }
    }
    
    //Business logic methods
    
    /**
     * Checks whether this coupon is valid for use
     * @param \DateTime $date date of coupon usage
     * @return boolean
     */
    public function isValidForUse() {
        if(self::STATUS_INVALID == $this->status) {
            return false;
        }
        
        //coupon is not valid if quantity is already at 0
        if(0 >= intval($this->quantity)) {
            return false;
        }
        
        $date = new \DateTime();
        //coupon is not valid if current time is not within coupon's start end expiry date        
        if($date < $this->startDate || $date >= $this->expiryDate) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Returns the amount given from param after substracted by discount
     * @param integer $amount amount to be subtracted
     */
    public function substractByDiscount($amount) {
        if(self::TYPE_VALUE == $this->type) {
            return $amount - $this->value;
        }
        //else type is percentage
        return $amount - ($amount * $this->value /100);
    }
}