<?php
namespace Emoneygw\Model\Concrete;

use Emoneygw\Model\GetterSetterTrait;
use Emoneygw\Model\LoadedFromStorageTrait;
use Emoneygw\Model\IsModifiedTrait;
use Emoneygw\Model\ModelInterface;

/**
 * Emoneygw\Model\Concrete\Product
 * 
 * @author Andi Wijaya
 * @version 1
 * Domain model for product
 */

class Product implements ModelInterface
{
    use GetterSetterTrait;
    use LoadedFromStorageTrait;
    use IsModifiedTrait;
    
    /**
     * @const STATUS_ACTIVE product is currently still being sold
     */
    const STATUS_ACTIVE = 'A';
	
    /**
     * @const STATUS_INACTIVE product is not being sold
     */
    const STATUS_INACTIVE = 'I';
    
    /**
     * array of status labels
     * 
     * @var array
     * @static
     */
    private static $_statusLabels = array(self::STATUS_ACTIVE => 'Active',
                                          self::STATUS_INACTIVE => 'Inactive'
                                         );
	
    /**
     * product id
     * @var string
     */
    private $id;
    
    /**
     * product name
     * @var string 
     */
    private $name;
    
    /**
     * product description
     * @var string
     */
    private $description;
    
    /**
     * product status
     * @var string
     */
    private $status;
	
    /**
     * stock quantity
     * @var integer
     */
    private $stock;
    
    /**
     * price
     * @var float
     */
    private $price;
    
    /**
     * Get id
     * @return string
     */ 
    public function getId() {
        return $this->id;
    }
    
    /**
     * get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * get description
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
	
    /**
     * Get current stock
     * @return integer
     */
    public function getStock() {
        return $this->stock;
    }

    /**
     * Get price
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }
    
    /**
     * Get status
     * @return string
     */
    public function getStatus() {
        return $this->status;
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
    
    //===================================================================================================================
    // Setter methods, note that validation performed are only on data format, not business logic
    
    /**
     * Set Id
     * @param $id value to set
     * @throws \InvalidArgumentException
     */
    public function setId($id) {
        if(empty($id))
            throw new \InvalidArgumentException('Id cannot be empty');
        
        $this->id = $id;
    }
    
    /**
     * Set name
     * @param string $name value to set
     */
    public function setName($name) {
        $this->name = $name;
    }
   
    /**
     * Set description
     * @param string $desc value to set
     */
    public function setDescription($desc) {
        $this->description = $desc;
    }
    
    /**
     * Set stock
     * @param integer $stock value to set
     */
    public function setStock($stock) {
        $this->stock = intval($stock);
    }
    
    /**
     * Set price
     * @param float $price value to set
     */
    public function setPrice($price) {
        $this->price = $price;
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
     * @inheritDoc
     * 
     */
    public function getObjectDetail($encode = true) {
        $tmp = new \stdClass();
        $tmp->id = $this->id;
        $tmp->name = $this->name;
        $tmp->description = $this->description;
        $tmp->status = $this->getStatusLabel($this->status);
        $tmp->stock = $this->stock;
        $tmp->price = $this->price;
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
        $this->name = $array['name'];
        $this->description = $array['description'];
        $this->stock = $array['stock'];
        $this->price = $array['price'];
        $this->status = $array['status'];
        
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getArrayCopy() {
        //do not extract statistics, this will be performed by statistics hydrator
        $array = array();
        $array['id'] = $this->id;
        $array['name'] = $this->name;
        $array['description'] = $this->description;
        $array['stock'] = $this->stock;
        $array['price'] = $this->price;
        $array['status'] = $this->status;
		
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
        if($this->name != $this->_snapshot->name) {
            return true;
        }
        if($this->description != $this->_snapshot->description) {
            return true;
        }
	if($this->stock != $this->_snapshot->stock) {
            return true;
        }
        if($this->price != $this->_snapshot->price) {
            return true;
        }
	if($this->status != $this->_snapshot->status) {
            return true;
        }
        
        //all object properties are identical with snapshot, object was not modified, return false
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    private function initializeSnapshot() {
        //all proprties are native datatypes (a deep clone is automatically created), no initialization on any property needed
    }
    
    //Business logic methods
    
    /**
     * Checks whether this product can be ordered
     * @param integer @orderQuantity product quantity to order, defaults to 1
     * @return boolean
     */
    public function canBeOrdered($orderQuantity = 1) {
        $orderQuantity = intval($orderQuantity);
        if(self::STATUS_INACTIVE == $this->status) {
            return false;
        }
        if(0 >= $this->stock - $orderQuantity) {
            return false;
        }
        return true;
    }
}