<?php
namespace Emoneygw\ValueObject\Concrete;

use Emoneygw\Model\Concrete\Product;
use Emoneygw\Model\GetterSetterTrait;
use Emoneygw\Model\LoadedFromStorageTrait;
use Emoneygw\Model\IsModifiedTrait;
use Emoneygw\ValueObject\ValueObjectInterface;

/**
 * Emoneygw\ValueObject\Concrete\OrderItem
 * 
 * @author Andi Wijaya
 * @version 1
 * Valueobject for representation of order item
 */

class OrderItem implements ValueObjectInterface
{
    use GetterSetterTrait;
    use LoadedFromStorageTrait;
    use IsModifiedTrait;
    
    /**
     * id (representing artificial id in storage)
     * NOTE: Normally a value object does not and should not contain an id, but this id field is needed on some database operations (object-relational impedance mismatch problem)
     * the catch is, this makes a domain model and value object to have no difference, therefore there is no difference if this class just implements a ModelInterface instead
     * @var integer
     */
    protected $id = null;
    
    /**
     * Product of this item order
     * @var \Emoneygw\Model\Concrete\Product
     */
    protected $product = null;
    
    /**
     * Quantity of this order item
     * @var integer
     */        
    protected $productQuantity;
    
    //Getter methods
    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get product of order item
     * @return \Emoneygw\Model\Concrete\Product
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Get quantity of order item
     * @return integer
     */
    public function getProductQuantity() {
        return $this->productQuantity;
    }
    
    //Setter methods
    /**
     * Set id of settlement detail
     * @param integer $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Set product 
     * @param \Emoneygw\Model\Concrete\Product|string $product
     */
    public function setProduct(Product $product) {
        $this->product = $product;
    }

    /**
     * Set product quantity
     * @param integer $quantity
     */
    public function setProductQuantity($quantity) {
        $this->productQuantity = intval($quantity);
    }
    
    /**
     * @inheritDoc
     * 
     */
    public function getObjectDetail($encode = true) {
        $tmp = new \stdClass();      
        $tmp->product = ($this->product instanceof Product)? $this->product->getObjectDetail($encode) : $this->product;
        $tmp->productQuantity = $this->productQuantity;
        
        if(false === $encode) {
            return $tmp;
        }
        else {   
            return \Zend\Json\Json::encode($tmp);
        }
    }
    
    //Hydrator methods
    //======================================================================================================
    /*
     * {@inheritDoc}
     */
    public function populate($array) {
        $this->id = $array['id'];
        //$this->product = $array['product']; //NOTE: do not populte here, datamapper hydrator will populate product
        $this->productQuantity = $array['productQuantity'];
        
        return $this;
    }
    
    /*
     * {@inheritDoc}
     */
    public function getArrayCopy() {
        $array = array();
        $array['id'] = $this->id;
        $array['product'] = ($this->product instanceof Product) ? $this->product->id : $this->product; //NOTE: set the id of the product here
        $array['productQuantity'] = $this->productQuantity;
        
        return $array;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isModified() {
        if(is_null($this->_snapshot)) {
            //no snapshot found = can't compare = can't tell whether this object was modified or not, but return true so datamapper will save record anyway (to err on the safe side)
            //update: current use case is that snapshot in model object will only be initialized when the object was loaded from database (in datamapper code), and never when object was created manually from other code,
            //in this case, it's safe to assume that no snapshot in the model object equals to no modification was performed
            return false;
        }
        
        //manually compare all of the field values of the snapshot with current field values of the domain model object (tedious task)
        if($this->id != $this->_snapshot->id) {
            return true;
        }
        if($this->productQuantity != $this->_snapshot->productQuantity) {
            return true;
        }
        
        //special treatment for product, since it is supposedly an instance of Product
        //we only compare the id of the product in snapshot against the id of the product in this object
        if($this->product->id != $this->_snapshot->product) {
            return true;
        }
        
        //all object properties are identical with snapshot, object was not modified, return false
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function initializeSnapshot() {
        //NOTE: no need to initialize snapshot (like in the following code), since this behaviour is automatic when an object is cloned in php
        //$this->_snapshot->product = $this->product; //both this object's 'product' property and this object's snapshot's 'product' property will refer to the same product object
    }
    
    //note: no business logic methods required
}