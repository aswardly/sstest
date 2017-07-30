<?php
namespace Emoneygw\Model\Concrete;

use Emoneygw\Model\Concrete\Coupon;
use Emoneygw\Model\Concrete\Product;
use Emoneygw\ValueObject\Concrete\OrderItem;
use Emoneygw\Generator\UUID;

use Emoneygw\Model\GetterSetterTrait;
use Emoneygw\Model\LoadedFromStorageTrait;
use Emoneygw\Model\IsModifiedTrait;
use Emoneygw\Model\ModelInterface;

/**
 * Emoneygw\Model\Concrete\Order
 * 
 * @author Andi Wijaya
 * @version 1
 * Domain model for order
 */

class Order implements ModelInterface
{
    use GetterSetterTrait;
    use LoadedFromStorageTrait;
    use IsModifiedTrait;
    
    /**
     * @const STATUS_DRAFT order is not submitted yet
     */
    const STATUS_DRAFT = 'D';
	
    /**
     * @const STATUS_SUBMITTED order is already submitted
     */
    const STATUS_SUBMITTED = 'S';
    
    /**
     * @const APPROVED order is approved by admin
     */
    const STATUS_APPROVED = 'A';
    
    /**
     * @const REJECTED order is rejected by admin
     */
    const STATUS_REJECTED = 'R';
    
    /**
     * @const STATUS_ON_PROCESS order is on process
     */
    const STATUS_ON_PROCESS = 'O';
    
    /**
     * @const STATUS_ON_DELIVERY order is on delivery
     */
    const STATUS_ON_DELIVERY = 'DL';
    
    /**
     * @const STATUS_DELIVERED order is already delivered
     */
    const STATUS_DELIVERED = 'DV';
    //NOTE: for the sake of demo simplicity, we assume that the courier never fails delivery, so there will never be an order that fails due to delivery issues
    
    /**
     * array of status labels
     * 
     * @var array
     * @static
     */
    private static $_statusLabels = array(self::STATUS_DRAFT => 'Draft',
                                          self::STATUS_SUBMITTED => 'Submitted',
                                          self::STATUS_APPROVED => 'Approved',
                                          self::STATUS_REJECTED => 'Rejected',
                                          self::STATUS_ON_PROCESS => 'On Process',
                                          self::STATUS_ON_DELIVERY => 'On Delivery',
                                          self::STATUS_DELIVERED => 'Delivered',
                                         );
    /**
     * @const SHIP_STATUS_NOT_READY shipping is not ready (order not processed yet)
     */
    const SHIP_STATUS_NOT_READY = 'N';
    
    /**
     * @const SHIP_STATUS_ON_PROCESS shipping is on process
     */
    const SHIP_STATUS_ON_PROCESS = 'O';
	
    /**
     * @const SHIP_STATUS_DELIVERED shipping done, items successfully delivered
     */
    const SHIP_STATUS_DELIVERED = 'D';
    //NOTE: for the sake of demo simplicity, we assume that the courier never fails delivery, so there will never be an order that fails due to delivery issues
    
    /**
     * array of shipping status labels
     * 
     * @var array
     * @static
     */
    private static $_shipStatusLabels = array(self::SHIP_STATUS_NOT_READY => 'Not Ready',
                                              self::SHIP_STATUS_ON_PROCESS => 'On Process',
                                              self::SHIP_STATUS_DELIVERED => 'Delivered'
                                             );
	
    /**
     * order id
     * @var string
     */
    private $id = null;
    
    /**
     * order created date
     * @var \DateTime
     */
    private $createdDate;
    
    /**
     * order submitted date
     * @var \DateTime
     */
    private $submittedDate;
    
    /**
     * order processed date
     * @var \DateTime
     */
    private $processedDate;
    
    /**
     * order status
     * @var string
     */
    private $status = self::STATUS_DRAFT; //default status on object creation time is draft
    
    /**
     * order total amount
     * @var float
     */
    private $totalAmount;
    
    /**
     * order coupon
     * @var \Emoneygw\Model\Concrete\Coupon
     */
    private $coupon;

    /**
     * customer name
     * @var string
     */
    private $customerName = null; 
    
    /**
     * customer phone
     * @var string
     */
    private $customerPhone = null;

    /**
     * customer email
     * @var string
     */
    private $customerEmail = null;
    
    /**
     * customer address
     * @var string
     */
    private $customerAddress = null;
    
    /**
     * shipping id
     * @var string
     */
    private $shippingId;
    
    /**
     * shipping 
     * @var string
     */
    private $shippingStatus = self::SHIP_STATUS_NOT_READY; //default status on object creation time is not ready
    
    /**
     * Order items
     * @var array 
     */
    private $items = array();
    
    /**
     * Special purpose container for teporary storage of deleted items
     * @var array
     */
    private $_deletedItems = array();
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->createdDate = new \DateTime();
    }
    
    /**
     * Get id
     * @return string
     */ 
    public function getId() {
        return $this->id;
    }
    
    /**
     * get created date
     * @return \DateTime
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }
    
    /**
     * get processed date
     * @return \DateTime
     */
    public function getProcessedDate() {
        return $this->processedDate;
    }
    
    /**
     * get submitted date
     * @return \DateTime
     */
    public function getSubmittedDate() {
        return $this->submittedDate;
    }
    
    /**
     * get status
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
	
    /**
     * get total amount
     * @return float
     */
    public function getTotalAmount() {
        return $this->totalAmount;
    }
    
    /**
     * get coupon
     * @return null|\Emoneygw\Model\Concrete\Coupon
     */
    public function getCoupon() {
        return $this->coupon;
    }
    
    /**
     * Get customer name
     * @return string
     */
    public function getCustomerName() {
        return $this->customerName;
    }

    /**
     * Get customer phone
     * @return string
     */
    public function getCustomerPhone() {
        return $this->customerPhone;
    }
    
    /**
     * Get customer email
     * @return string
     */
    public function getCustomerEmail() {
        return $this->customerEmail;
    }
    
    /**
     * Get customer address
     * @return string
     */
    public function getCustomerAddress() {
        return $this->customerAddress;
    }
    
    /**
     * Get shippingId
     * @return string
     */
    public function getShippingId() {
        return $this->shippingId;
    }
    
    /**
     * Get shipping status
     * @return string
     */
    public function getShippingStatus() {
        return $this->shippingStatus;
    }
    
    /**
     * Get order items
     * @return array
     */
    public function getItems() {
        return $this->items;
    }
    
    /**
     * Get deleted items
     * @return array
     */
    public function getDeletedItems() {
        return $this->_deletedItems;
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
     * Get label for a certain shipping status
     * @param $status
     * @return string
     */
    public function getShipStatusLabel($status) {
        if(in_array($status, array_keys(self::$_shipStatusLabels))) {
            return self::$_shipStatusLabels[$status];
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
     * Set created date
     * @param \DateTime $date value to set
     */
    public function setCreatedDate(\DateTime $date) {
        $this->createdDate = $date;
    }
    
    /**
     * Set processed date
     * @param \DateTime $date value to set
     */
    public function setProcessedDate(\DateTime $date) {
        $this->processedDate = $date;
    }
    
    /**
     * Set submitted date
     * @param \DateTime $date value to set
     */
    public function setSubmittedDate(\DateTime $date) {
        $this->submittedDate = $date;
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
     * Set total amount
     * @param string $amount value to set
     */
    public function setTotalAmount($amount) {
        $this->totalAmount = floatval($amount);
    }
    
    /**
     * Set type
     * @param \Emoneygw\Model\Concrete\Coupon $coupon value to set
     */
    public function setCoupon(Coupon $coupon) {
        $this->coupon = $coupon;
    }
    
    /**
     * Set customer name
     * @param string $name value to set
     */
    public function setCustomerName($name) {
        $this->customerName = $name;
    }
    
    /**
     * Set customer phone
     * @param string $phone value to set
     */
    public function setCustomerPhone($phone) {
        //validate phone no with regex (assumption: phone no is 5 digit number or more)
        if(false == preg_match('/\d{5,}/', $phone)) {
            throw new \InvalidArgumentException('Invalid phone format');
        }
        $this->customerPhone = $phone;
    }
    
    /**
     * Set customer email
     * @param string $email value to set
     */
    public function setCustomerEmail($email) {
        //validate email format with simple regex that matches something@somedomain.tld
        if(false == preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/', $email)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
        $this->customerEmail = $email;
    }
    
    /**
     * Set customer address
     * @param string $address value to set
     */
    public function setCustomerAddress($address) {
        $this->customerAddress = $address;
    }
    
    /**
     * Set shipping id
     * @param string $id value to set
     */
    public function setShippingId($id) {
        $this->shippingId = $id;
    }
    
    /**
     * Set shipping status
     * @param string $status value to set
     */
    public function setShippingStatus($status) {
        if(false === in_array($status, array_keys(self::$_shipStatusLabels))) {
            throw new \InvalidArgumentException("Invalid setting of shipping status value");
        }
        $this->shippingStatus = $status;
    }
    
    /**
     * Set order items
     * @param array $items value to set
     */
    public function setItems(array $items) {
        $this->items = $items;
    }
    
    /**
     * @inheritDoc
     * 
     */
    public function getObjectDetail($encode = true) {
        $tmp = new \stdClass();
        $tmp->id = $this->id;
        //NOTE: date formats are hardcoded for now
        $tmp->createdDate = ($this->createdDate instanceof \DateTime)? $this->createdDate->format('Y-m-d H:i:s') : $this->createdDate;
        $tmp->submittedDate = ($this->submittedDate instanceof \DateTime)? $this->submittedDate->format('Y-m-d H:i:s') : $this->submittedDate;
        $tmp->processedDate = ($this->processedDate instanceof \DateTime)? $this->processedDate->format('Y-m-d H:i:s') : $this->processedDate;
        $tmp->status = $this->getStatusLabel($this->status);
        $tmp->totalAmount = $this->totalAmount;
        $tmp->coupon = ($this->coupon instanceof Coupon)? $this->coupon->getObjectDetail($encode) : $this->coupon;
        $tmp->customerName = $this->customerName;
        $tmp->customerEmail = $this->customerEmail;
        $tmp->customerPhone = $this->customerPhone;
        $tmp->customerAddress = $this->customerAddress;
        $tmp->shippingId = $this->shippingId;
        $tmp->shippingStatus = $this->getShipStatusLabel($this->shippingStatus);
        $tmp->orderItems = array();
        foreach($this->items as $orderItem) {
            $itemInfo = ($orderItem instanceof OrderItem) ? $orderItem->getObjectDetail($encode) : $orderItem;
            array_push($tmp->orderItems, $itemInfo);
        }
        
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
        $this->createdDate = $array['createdDate'];
        $this->processedDate = $array['processedDate'];
        $this->submittedDate = $array['submittedDate'];
        $this->status = $array['status'];
        $this->totalAmount = $array['totalAmount'];
        //$this->coupon = $array['coupon']; //NOTE: do not populte here, datamapper hydrator will populate coupon
        $this->customerName = $array['customerName'];
        $this->customerPhone = $array['customerPhone'];
        $this->customerEmail = $array['customerEmail'];
        $this->customerAddress = $array['customerAddress'];
        $this->shippingId = $array['shippingId'];
        $this->shippingStatus = $array['shippingStatus'];
        //NOTE: do not set items here, items is set in datamapper hydrator code
        
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getArrayCopy() {
        $array = array();
        $array['id'] = $this->id;
        $array['createdDate'] = $this->createdDate;
        $array['processedDate'] = $this->processedDate;
        $array['submittedDate'] = $this->submittedDate;
        $array['status'] = $this->status;
        $array['totalAmount'] = $this->totalAmount;
        $array['coupon'] = ($this->coupon instanceof Coupon) ? $this->coupon->id : $this->coupon; //NOTE: set the id of the coupon if coupon is set
        $array['customerName'] = $this->customerName;
        $array['customerPhone'] = $this->customerPhone;
        $array['customerEmail'] = $this->customerEmail;
        $array['customerAddress'] = $this->customerAddress;
        $array['shippingId'] = $this->shippingId;
        $array['shippingStatus'] = $this->shippingStatus;        
        //NOTE: do not extract items, this is performed by datamapper hydrator code
        
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
        if($this->id != $this->_snapshot->getId()) {
            return true;
        }
        if($this->createdDate != $this->_snapshot->getCreatedDate()) {
            return true;
        }
        if($this->processedDate != $this->_snapshot->getProcessedDate()) {
            return true;
        }
        if($this->submittedDate != $this->_snapshot->getSubmittedDate()) {
            return true;
        }
        if($this->status != $this->_snapshot->getStatus()) {
            return true;
        }
	if($this->totalAmount != $this->_snapshot->getTotalAmount()) {
            return true;
        }
        if($this->coupon != $this->_snapshot->getCoupon()) {
            return true;
        }
	if($this->customerName != $this->_snapshot->getCustomerName()) {
            return true;
        }
        if($this->customerPhone != $this->_snapshot->getCustomerPhone()) {
            return true;
        }
        if($this->customerEmail != $this->_snapshot->getCustomerEmail()) {
            return true;
        }
        if($this->customerAddress != $this->_snapshot->getCustomerAddress()) {
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
        if($this->createdDate instanceof \DateTime) {
            $this->_snapshot->createdDate = clone $this->createdDate;
        }
        if($this->processedDate instanceof \DateTime) {
            $this->_snapshot->processedDate = clone $this->processedDate;
        }
        if($this->submittedDate instanceof \DateTime) {
            $this->_snapshot->submittedDate = clone $this->submittedDate;
        }
        
        //NOTE: no need to initialize items, by default the snapshot will refer to the same instance
        //we use a special purpose container for detecting items that got deleted
    }
    
    //business logic methods
    
    /**
     * Get item if it exists in order
     * Returns OrderItem instance or false if item is not found
     * 
     * @param \Emoneygw\Model\Concrete\Product $item item to add into order
     * @return \Emoneygw\Model\Concrete\OrderItem|boolean
     */
    private function getItemFromOrder(Product $product) {
        foreach($this->items as $existingItem) {
            if($existingItem->product->id == $product->id) {
                //item found, return it
                return $existingItem;
            }
        }
        //item does not exist
        return false;
    }
    /**
     * Creates a new empty order (no items)
     */
    public function createOrder() {
        //this method is only for newly created order (not loaded from storage)
        if($this->_isLoadedFromStorage) {
            throw new \DomainException("Cannot reset order already persisted in storage");
        }
        //else this is a new order not yet persisted in storage
        //NOTE: for demo simplicity sake, no checking for existing order with same id is performed, the generator is considered reliable enough
        $this->id = 'ORD'.UUID::Generate(10); //generate new random order id
        $this->status = self::STATUS_DRAFT;
        $this->shippingStatus = self::SHIP_STATUS_NOT_READY;
        $this->createdDate = new \DateTime();
    }
    
    /**
     * Add product to order
     * If the item already exists in order, then add the order quantity to the item
     * 
     * @param \Emoneygw\Model\Concrete\Product $item item to add into order
     * @param integer $quantity quantity of item to add
     * @throws \DomainException
     */
    public function addItem(Product $item, $quantity) {
        if(self::STATUS_DRAFT != $this->status) {
            throw new \DomainException("Cannot add item, status is not draft");
        }
        $quantity = intval($quantity);
        
        //check whether item exists in order or not
        $orderItem = $this->getItemFromOrder($item);
        if($orderItem instanceof OrderItem) {
            //item exists in order, update quantity, but check stock and status first
            if(false === $item->canBeOrdered($orderItem->productQuantity + $quantity)) {
                throw new \DomainException("Product does not have enough stock or is inactive");
            }
            //item can be ordered
            $orderItem->productQuantity += $quantity;
            //recalculate total amount
            $this->calculateTotalAmount();
            
            return true;
        }
        //else item does not exist in order, add it , but check stock and status first
        if(false === $item->canBeOrdered($quantity)) {
            throw new \DomainException("Product does not have enough stock or is inactive");
        }
        //item can be added
        $newOrderItem = new OrderItem();
        $newOrderItem->product = $item;
        $newOrderItem->productQuantity = $quantity;
        array_push($this->items, $newOrderItem);
        
        //recalculate total amount
        $this->calculateTotalAmount();
        
        return true;
    }
    
    /**
     * Remove certain item from order
     * Returns true if operation was successful or false if item was not found in order
     * 
     * @param \Emoneygw\Model\Concrete\Product $item item to remove from order
     * @throws \DomainException
     * @return boolean|void
     */
    public function removeItem(Product $item) {
        if(self::STATUS_DRAFT != $this->status) {
            throw new \DomainException("Cannot remove item, status is not draft");
        }
        
        //NOTE: cannot use getItemFromOrder method since we need the array key when deleting
        foreach($this->items as $index => $existingItem) {
            if($existingItem->product->id == $item->id) {
                //update totalAmount
                $this->totalAmount -= ($existingItem->productQuantity * $existingItem->product->price);
                
                array_push($this->_deletedItems, $this->items[$index]);
                unset($this->items[$index]);
                
                //recalculate total amount
                $this->calculateTotalAmount();
                
                return true;
            }
        }
        //item does not exist in order
        throw new \DomainException("Item does not exist in order");
    }
    
    /**
     * Modify existing item quantity
     * Returns true if operation was successful or false if item was not found in order
     * 
     * @param \Emoneygw\Model\Concrete\Product $item item to add into order
     * @param integer $quantity quantity of item to add
     * @throws \DomainException
     * @return boolean
     */
    public function editItem(Product $item, $quantity) {
        if(self::STATUS_DRAFT != $this->status) {
            throw new \DomainException("Cannot edit item, status is not draft");
        }
        $quantity = intval($quantity);
        
        //check whether item exists in order or not
        $orderItem = $this->getItemFromOrder($item);
        if($orderItem instanceof OrderItem) {
            //item exists in order, update quantity, but check stock and status first
            if(false === $item->canBeOrdered($quantity)) {
                throw new \DomainException("Product does not have enough stock or is inactive");
            }
            //item can be ordered, update quantity
            $orderItem->productQuantity = $quantity;
            
            //recalculate total amount
            $this->calculateTotalAmount();
            
            return true;
        }
        //item does not exist in order
        throw new \DomainException("Item does not exist in order");
    }
    
    /**
     * Apply coupon to order
     * 
     * @param Emoneygw\Model\Concrete\Coupon $coupon coupon to apply
     * @param boolean $byCustomer flag indicating coupon application is performed by customer or not, defaults to true
     * @param boolean $submitFlag flag indicating coupon is applied during submit process or not, defaults to false
     * @param \DateTime $date date of coupon usage, defaults to null
     * @throws \DomainException
     */
    public function applyCoupon(Coupon $coupon) {
        
        if(false === $coupon->isValidForUse()) {
            throw new \DomainException("Coupon is not valid for use");
        }
        //else coupon is valid, 
        
        //order status must only be draft
        if(false === self::STATUS_DRAFT) {
            throw new \DomainException("Coupon cannot be applied because order is already submitted");
        }
        
        //check if order has items
        if(empty($this->items)) {
            throw new \DomainException("Cannot apply coupon to order with no items");
        }
        
        //store any existing coupon to a temp variable
        $tempCoupon = $this->coupon;
        $this->coupon = $coupon;
        
        //calculateTotalAmount 
        $this->calculateTotalAmount();
        
        //NOTE: assumption, coupon can only be used if totalAmount is at least 10000 after discount
        if(10000 > $this->totalAmount) {
            //restore previous coupon and recalculate total amount
            $this->coupon = $tempCoupon;
            $this->calculateTotalAmount();
            
            throw new \DomainException("Cannot apply coupon because amount becomes less than 10000");
        }
    }
    
    /**
     * Calculate total amount based on items set and coupon applied
     */
    private function calculateTotalAmount() {
        $totalAmount = 0;
        foreach($this->items as $item) {
            $totalAmount += $item->product->price * $item->productQuantity;
        }
        
        //apply coupon
        if($this->coupon instanceof Coupon) {
            if(Coupon::TYPE_VALUE == $this->coupon->type) {
                $totalAmount -= $this->coupon->value;
            }
            else { //type is percentage
                $totalAmount -= ( $this->coupon->value * $totalAmount / 100);
            }
        }
        $this->totalAmount = $totalAmount;
    }


    /**
     * Submit order for processing
     * 
     * @throws \DomainException
     */
    public function submitOrder() {
        if(self::STATUS_DRAFT != $this->status) {
            throw new \DomainException("Cannot submit order, status is not draft");
        }
        
        //check if order has items
        if(empty($this->items)) {
            throw new \DomainException("Order has no items");
        }
        
        //if order has coupon, check whether coupon is valid
        if($this->coupon instanceof Coupon) {
            if(false === $this->coupon->isValidForUse()) {
                throw new \DomainException("Cannot submit order, coupon is not valid or no longer valid");
            }
        }
        
        //recheck order items and recalculate total amount
        //this is necessary since the time order is created can be different when compared with the time order is submitted
        //NOTE: if any item is invalid then a DomainException is thrown (order is automatically rejected)
        foreach($this->items as $orderItem) {
            if(false === $orderItem->product->canBeOrdered($orderItem->productQuantity)) {
                throw new \DomainException("Order item for product: ".$orderItem->product->id.
                                           " is invalid. Status: ".$orderItem->product->getStatusLabel($orderItem->product->status).
                                           " (current stock: ".$orderItem->product->stock.
                                           ", ordered: ".$orderItem->productQuantity.")");
            }
        }
        
        //recalculate total amount (in case of price changes of products in order)
        $this->calculateTotalAmount();
        
        //additional check for total amount
        if(10000 > $this->totalAmount) {
            throw new \DomainException("Cannot submit order, total amount is less than 10000");
        }
        
        //validate other fields
        //NOTE for the sake of demo simplicity, each field validated is considered valid if the field values are not null
        if(is_null($this->customerName)) {
            throw new \DomainException("Customer name is invalid");
        }
        if(is_null($this->customerPhone)) {
            throw new \DomainException("Customer phone is invalid");
        }
        if(is_null($this->customerEmail)) {
            throw new \DomainException("Customer email is invalid");
        }
        if(is_null($this->customerAddress)) {
            throw new \DomainException("Customer address is invalid");
        }
        
        //all fields are considered valid, order can be submitted
        $this->status = self::STATUS_SUBMITTED;
        $this->submittedDate = new \DateTime();
        
        //update item stock
        foreach($this->items as $orderItem) {
            $orderItem->product->stock -= $orderItem->productQuantity;
        }
        
        //update coupon quantity
        $this->coupon->quantity--;
    }
    
    /**
     * Process order (after submission from customer)
     * @throws \DomainException
     */
    public function processOrder() {
        if(self::STATUS_SUBMITTED != $this->status) {
            throw new \DomainException("Cannot process order, status is not submitted");
        }
        
        //recheck order items and recalculate total amount
        //this is necessary since the time order is submitted can be different when compared with the time order is processed
        try {
            //recheck order items and recalculate total amount
            //this is necessary since the time order is submitted can be different when compared with the time order is processed
            //NOTE: if any item is invalid then a DomainException is thrown (order is automatically rejected)
            foreach($this->items as $orderItem) {
                if(false === $orderItem->product->canBeOrdered($orderItem->productQuantity)) {
                    throw new \DomainException("Order item for product: ".$orderItem->product->id.
                                               " is invalid. Status: ".$orderItem->product->getStatusLabel($orderItem->product->status).
                                               " (current stock: ".$orderItem->product->stock.
                                               ", ordered: ".$orderItem->productQuantity.")");
                }
            }
            
            //recalculate total amount (in case of price changes)
            //this is done for simplicity sake and under the assumption that the store will not want to incur losses even if customer has submitted order before price change was performed
            $this->calculateTotalAmount();
        }
        catch(\DomainException $e) {
            //reject order
            $this->status = self::STATUS_REJECTED;
            
            //recover coupon quantity (coupon was deducted on order submission previously)
            if($this->coupon instanceof Coupon) {
                //recover coupon quantity (coupon was deducted on order submission previously)
                $this->coupon->quantity++;
            }
            
            //recover stock due to order rejection
            foreach($this->items as $orderItem) {
                $orderItem->product->stock += $orderItem->productQuantity;
            }
            
            //rethrow exception, since we are only interested in updating order status
            throw $e;
        }
        
        //order is valid, update order status and item stock
        $this->status = self::STATUS_APPROVED;
        $this->processedDate = new \DateTime();
    }
    
    /**
     * Submit payment proof for this order
     * 
     * @param string 
     */
    public function submitPaymentProof($paymentProof) {
        //payment proof can only be submitted if order status is approved
        if(self::STATUS_APPROVED != $this->status) {
            throw new \DomainException("Cannot submit payment proof, order status is not approved");
        }
        
        //TODO: business logic of payment proof processing goes here
        //for the sake of simplicity of demo, consider processing is always perrformed successfully, so just update status
        $this->status = self::STATUS_ON_PROCESS;
    }
    
    /**
     * add shipping info
     * @param $shippingId shipping id
     * @param $shippingStatus shipping status
     * @throws \DomainException
     */
    public function addShippingInfo($shippingId) {
        //shipping info can only be updated if order status is on process
        if(self::STATUS_ON_PROCESS != $this->status) {
            throw new \DomainException("Cannot add shipping info, order status is not on process");
        }
        
        $this->shippingId = $shippingId;
        $this->shippingStatus = self::SHIP_STATUS_ON_PROCESS;
        $this->status = self::STATUS_ON_DELIVERY;
    }
    
    /**
     * update shipping info
     * @param $shippingId shipping id
     * @param $shippingStatus shipping status
     * @throws \DomainException
     */
    public function updateShippingInfo($shippingId, $shippingStatus) {
        //shipping info can only be updated if order status is on process
        if(self::STATUS_ON_DELIVERY != $this->status) {
            throw new \DomainException("Cannot update shipping info, order status is not on delivery");
        }
        
        $this->setShippingId($shippingId);
        $this->setShippingStatus($shippingStatus);
        
        if(self::SHIP_STATUS_DELIVERED == $this->shippingStatus) {
            $this->status = self::STATUS_DELIVERED;
        }
    }
    
    /**
     * Inquire shipping info
     * Returns an array with 2 elements (shipping id and shipping status) if request is valid
     * 
     * @return array
     * @throws \DomainException
     */
    public function inquireShippingInfo() {
        //shipping info can only be inquired if order status is on delivery
        if(self::STATUS_ON_DELIVERY != $this->status && self::STATUS_DELIVERED != $this->status) {
            throw new \DomainException("Cannot inquire shipping info, order status is not on delivery or delivered");
        }
        
        return array('orderId' => $this->id,
                     'shippingId' => $this->shippingId, 
                     'shippingStatus' => $this->getShipStatusLabel($this->shippingStatus));
    }
}