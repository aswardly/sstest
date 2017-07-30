<?php
/**
 * Emoneygw\Test\OrderProcessScenarioTest
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Scenario test involving: 
 * 
 * - order submission
 * - order processing
 */
namespace Emoneygw\Test;

use ApplicationTest\Bootstrap;
use PHPUnit_Framework_TestCase;

use Emoneygw\Model\Concrete\Order;
use Emoneygw\ValueObject\Concrete\OrderItem;
use Emoneygw\Model\Concrete\Product;
use Emoneygw\Model\Concrete\Coupon;

class OrderProcessScenarioTest extends \PHPUnit_Framework_TestCase
{
    protected $product;
    protected $anotherProduct;
    
    protected $coupon;
    protected $anotherCoupon;
    
    protected function setUp() {
        //create yesterday datetime
        $yesterdayDate= new \DateTime();
        $yesterdayDate->add(\DateInterval::createFromDateString('yesterday'));
        
        //create tommorow's date
        $tomorrowDate = new \DateTime();
        $tomorrowDate->add(new \DateInterval("P1D"));
        
        //initiate valid product
        $this->product = new Product();
        $this->product->setId('VALID01');
        $this->product->setName('Valid Product');
        $this->product->setDescription('Valid Product Desc');
        $this->product->setPrice(100000);
        $this->product->setStock(50);
        $this->product->setStatus(Product::STATUS_ACTIVE);
        
        //initiate another valid product
        $this->anotherProduct= new Product();
        $this->anotherProduct->setId('VALID02');
        $this->anotherProduct->setName('Valid Product 2');
        $this->anotherProduct->setDescription('Valid Product Desc 2');
        $this->anotherProduct->setPrice(5000);
        $this->anotherProduct->setStock(100);
        $this->anotherProduct->setStatus(Product::STATUS_ACTIVE);
        
        //initiate valid coupon (type: value)
        $this->coupon = new Coupon();
        $this->coupon->setId('VALIDC1');
        $this->coupon->setValue(10000);
        $this->coupon->setQuantity(100);
        $this->coupon->setStatus(Coupon::STATUS_VALID);
        $this->coupon->setType(Coupon::TYPE_VALUE);
        $this->coupon->setStartDate($yesterdayDate); //set valid from yesterday
        $this->coupon->setExpiryDate($tomorrowDate); //set valid until tomorrow
        
        //initiate valid coupon (type: percentage)
        $this->anotherCoupon = new Coupon();
        $this->anotherCoupon->setId('VALIDC2');
        $this->anotherCoupon->setValue(10); //10% discount
        $this->anotherCoupon->setQuantity(5000);
        $this->anotherCoupon->setStatus(Coupon::STATUS_VALID);
        $this->anotherCoupon->setType(Coupon::TYPE_PERCENTAGE);
        $this->anotherCoupon->setStartDate($yesterdayDate); //set valid from yesterday
        $this->anotherCoupon->setExpiryDate($tomorrowDate); //set valid until tomorrow
    }

    public function testValidOrderSubmission() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        $newOrder->customerName = 'John Smith';
        $newOrder->customerPhone = '08123456789';
        $newOrder->customerEmail = 'johnsmith@somewhere.com';
        $newOrder->customerAddress = 'Some Street in some city';
        
        //check that submittedDate and processedDate are still null
        $this->assertEmpty($newOrder->submittedDate);
        $this->assertEmpty($newOrder->processedDate);
        
        //add 1 type ofitem
        $newOrder->addItem($this->product, 2);
                        
        //add another type of item
        $newOrder->addItem($this->anotherProduct, 3);
        
        //apply coupon
        $newOrder->applyCoupon($this->coupon);
        
        //try submitting order
        $newOrder->submitOrder();
        
        //check order status
        $this->assertEquals(Order::STATUS_SUBMITTED, $newOrder->status);
        $this->assertInstanceOf('\DateTime', $newOrder->submittedDate);
        
        //check quantity of products
        //find product that was in submitted order, check quantity
        $orderItem = false;
        $anotherOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID01' == $item->product->id) {
                $orderItem = $item;
            }
            else if('VALID02' == $item->product->id) {
                $anotherOrderItem = $item;
            }
        }
        //both items shoud have been found
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $orderItem);
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $anotherOrderItem);
        
        //check both item product stock (both stock and coupon quantity should have been deducted)
        $this->assertEquals(48, $orderItem->product->stock);
        $this->assertEquals(97, $anotherOrderItem->product->stock);
        
        //NOTE: or just use the following code for this scenario, note that the Product objects in OrderItem objects are just references to the product created during setup
        $this->assertEquals(48, $this->product->stock);
        $this->assertEquals(97, $this->anotherProduct->stock);
        
        //check coupon quantity
        $this->assertEquals(99, $newOrder->coupon->quantity);
        //NOTE: or just use the following code for this scenario, note that the Coupon object in Order object is also just a reference to the coupon created during setup
        $this->assertEquals(99, $this->coupon->quantity);
    }
    
    public function testValidOrderProcess() {
        $this->product->stock = 50;
        $this->anotherProduct->stock = 100;
        $this->coupon->quantity = 100;
        
        //setup order submission
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        $newOrder->customerName = 'John Smith';
        $newOrder->customerPhone = '08123456789';
        $newOrder->customerEmail = 'johnsmith@somewhere.com';
        $newOrder->customerAddress = 'Some Street in some city';
        
        //check that submittedDate and processedDate are still null
        $this->assertEmpty($newOrder->submittedDate);
        $this->assertEmpty($newOrder->processedDate);
        
        //add 1 type ofitem
        $newOrder->addItem($this->product, 2);
                        
        //add another type of item
        $newOrder->addItem($this->anotherProduct, 3);
        
        //apply coupon
        $newOrder->applyCoupon($this->coupon);
        
        //try submitting order
        $newOrder->submitOrder();
        
        //check order status
        $this->assertEquals(Order::STATUS_SUBMITTED, $newOrder->status);
        
        //Now let's try processing order
        $newOrder->processOrder();
        //check status, it should become approved
        $this->assertEquals(Order::STATUS_APPROVED, $newOrder->status);
        $this->assertInstanceOf('\DateTime', $newOrder->processedDate);
    }
    
    public function testInvalidOrderProcess() {
        //setup some objects
        $this->product->stock = 50;
        $this->anotherProduct->stock = 100;
        $this->coupon->quantity = 100;
        
        //setup order submission
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        $newOrder->customerName = 'John Smith';
        $newOrder->customerPhone = '08123456789';
        $newOrder->customerEmail = 'johnsmith@somewhere.com';
        $newOrder->customerAddress = 'Some Street in some city';
        
        //check that submittedDate and processedDate are still null
        $this->assertEmpty($newOrder->submittedDate);
        $this->assertEmpty($newOrder->processedDate);
        
        //add 1 type ofitem
        $newOrder->addItem($this->product, 2);
                        
        //add another type of item
        $newOrder->addItem($this->anotherProduct, 3);
        
        //apply coupon
        $newOrder->applyCoupon($this->coupon);
        
        //try submitting order
        $newOrder->submitOrder();
        
        //check order status
        $this->assertEquals(Order::STATUS_SUBMITTED, $newOrder->status);
        
        //check product stock after successful submission
        $this->assertEquals(48, $this->product->stock);
        $this->assertEquals(97, $this->anotherProduct->stock);
        
        //check coupon quantity after successful submission
        $this->assertEquals(99, $this->coupon->quantity);
        
        //Now, let's say an item has suddenly become invalid after order is submitted but before order is processed
        //find a crtain product in order
        $foundOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID01' == $item->product->id) {
                $foundOrderItem = $item;
                break;
            }
        }
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $foundOrderItem);
        
        $foundOrderItem->product->status = Product::STATUS_INACTIVE;
        //or just this (same effect)
        //$this->product->status = Product::STATUS_INACTIVE;

        $this->expectException(\DomainException::class);
        //Now let's try processing order
        $newOrder->processOrder();
        
        //check status, it should become rejected
        $this->assertEquals(Order::STATUS_REJECTED, $newOrder->status);
        $this->assertEmpty($newOrder->processedDate); //processedDate should still be empty (null)
        
         //check quantity of products
        //find product that was in the order, check quantity
        $orderItem = false;
        $anotherOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID01' == $item->product->id) {
                $orderItem = $item;
            }
            else if('VALID02' == $item->product->id) {
                $anotherOrderItem = $item;
            }
        }
        //both items shoud have been found
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $orderItem);
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $anotherOrderItem);
        var_dump($orderItem);
        //check both item product stock (should have been restored due to failed order processing)
        $this->assertEquals(52, $orderItem->product->stock);
        $this->assertEquals(103, $anotherOrderItem->product->stock);
        
        //NOTE: or just use the following code for this scenario, note that the Product objects in OrderItem objects are just references to the product created during setup
        $this->assertEquals(52, $this->product->stock);
        $this->assertEquals(103, $this->anotherProduct->stock);
        
        //check coupon quantity (should have been restored due to failed order processing)
        $this->assertEquals(101, $newOrder->coupon->quantity);
        //NOTE: or just use the following code for this scenario, note that the Coupon object in Order object is also just a reference to the coupon created during setup
        $this->assertEquals(101, $this->anotherCoupon->quantity);
    }
    
    
    
    public function testInvalidOrderSubmission() {
        //setup coupon and products
        $this->product->stock = 50;
        $this->anotherProduct->stock = 100;
        $this->anotherCoupon->quantity = 5000;
        
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        $newOrder->customerName = 'John Smith Again';
        $newOrder->customerPhone = '08123456789';
        $newOrder->customerEmail = 'johnsmith@somewhere.com';
        $newOrder->customerAddress = 'Some Street in some city';
        
        //add 1 type ofitem
        $newOrder->addItem($this->product, 2);
                        
        //add another type of item
        $newOrder->addItem($this->anotherProduct, 3);
        
        //now, let's say that an item has suddenly become inactive
        $this->product->status = Product::STATUS_INACTIVE;
        
        //apply coupon
        $newOrder->applyCoupon($this->anotherCoupon); //10% discount
        
        $this->expectException(\DomainException::class);
        //try submitting order
        $newOrder->submitOrder();
        
        //check order status, it must still be draft
        $this->assertEquals(Order::STATUS_DRAFT, $newOrder->status);
        
        //check quantity of products
        //find product that was in submitted order, check quantity
        $orderItem = false;
        $anotherOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID01' == $item->product->id) {
                $orderItem = $item;
            }
            else if('VALID02' == $item->product->id) {
                $anotherOrderItem = $item;
            }
        }
        //both items shoud have been found
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $orderItem);
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $anotherOrderItem);
        var_dump($orderItem);
        //check both item product stock (both stock and coupon quantities should still be the same)
        $this->assertEquals(50, $orderItem->product->stock);
        $this->assertEquals(100, $anotherOrderItem->product->stock);
        
        //NOTE: or just use the following code for this scenario, note that the Product objects in OrderItem objects are just references to the product created during setup
        $this->assertEquals(50, $this->product->stock);
        $this->assertEquals(100, $this->anotherProduct->stock);
        
        //check coupon quantity
        $this->assertEquals(5000, $newOrder->coupon->quantity);
        //NOTE: or just use the following code for this scenario, note that the Coupon object in Order object is also just a reference to the coupon created during setup
        $this->assertEquals(5000, $this->anotherCoupon->quantity);
        
        
        
        
        
        
        
        
        //Now, let's try that again with a different case
        //restore the product status that was deactivated
        $this->product->status = Product::STATUS_ACTIVE;
        
        //this time, let's mark the coupon as invalid
        $newOrder->coupon->status = Coupon::STATUS_INVALID;
        
        $this->expectException(\DomainException::class);
        //try submitting again
        $newOrder>submitOrder();
        
        //check order status, it must still be draft
        $this->assertEquals(Order::STATUS_DRAFT, $newOrder->status);
        
        //check quantity of products
        //find product that was in submitted order, check quantity
        $orderItem = false;
        $anotherOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID01' == $item->product->id) {
                $orderItem = $item;
            }
            else if('VALID02' == $item->product->id) {
                $anotherOrderItem = $item;
            }
        }
        //both items shoud have been found
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $orderItem);
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $anotherOrderItem);
        
        //check both item product stock (both stock and coupon quantities should still be the same)
        $this->assertEquals(50, $orderItem->product->stock);
        $this->assertEquals(100, $anotherOrderItem->product->stock);
        
        //NOTE: or just use the following code for this scenario, note that the Product objects in OrderItem objects are just references to the product created during setup
        $this->assertEquals(50, $this->product->stock);
        $this->assertEquals(100, $this->anotherProduct->stock);
        
        //check coupon quantity
        $this->assertEquals(5000, $newOrder->coupon->quantity);
        //NOTE: or just use the following code for this scenario, note that the Coupon object in Order object is also just a reference to the coupon created during setup
        $this->assertEquals(5000, $this->anotherCoupon->quantity);
        
        
        
        
        
        
        
        
        //One more time, this time let's make the coupon expired
        //restore the coupon status
        $newOrder->coupon->status = Coupon::STATUS_VALID;
        
        $yesterdayDate= new \DateTime();
        $yesterdayDate->add(\DateInterval::createFromDateString('yesterday'));
        
        //set the coupon expiry date to past date
        $newOrder->coupon->expiryDate($yesterdayDate);
        
        $this->expectException(\DomainException::class);
        //try submitting again
        $newOrder>submitOrder();
        
        //check order status, it must still be draft
        $this->assertEquals(Order::STATUS_DRAFT, $newOrder->status);
        
        //check quantity of products
        //find product that was in submitted order, check quantity
        $orderItem = false;
        $anotherOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID01' == $item->product->id) {
                $orderItem = $item;
            }
            else if('VALID02' == $item->product->id) {
                $anotherOrderItem = $item;
            }
        }
        //both items shoud have been found
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $orderItem);
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $anotherOrderItem);
        
        //check both item product stock (both stock and coupon quantities should still be the same)
        $this->assertEquals(50, $orderItem->product->stock);
        $this->assertEquals(100, $anotherOrderItem->product->stock);
        
        //NOTE: or just use the following code for this scenario, note that the Product objects in OrderItem objects are just references to the product created during setup
        $this->assertEquals(50, $this->product->stock);
        $this->assertEquals(100, $this->anotherProduct->stock);
        
        //check coupon quantity
        $this->assertEquals(5000, $newOrder->coupon->quantity);
        //NOTE: or just use the following code for this scenario, note that the Coupon object in Order object is also just a reference to the coupon created during setup
        $this->assertEquals(5000, $this->anotherCoupon->quantity);
    }
}