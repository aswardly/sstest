<?php
/**
 * Emoneygw\Test\OrderItemsScenarioTest
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Scenario test involving: 
 * - addition, modification, and deletion of products, to order 
 * - application of coupon to an order
 * - checking whether the calculated amount is correct after modification of items in order and application of coupon
 */
namespace Emoneygw\Test;

use ApplicationTest\Bootstrap;
use PHPUnit_Framework_TestCase;

use Emoneygw\Model\Concrete\Order;
use Emoneygw\ValueObject\Concrete\OrderItem;
use Emoneygw\Model\Concrete\Product;
use Emoneygw\Model\Concrete\Coupon;

class OrderItemsScenarioTest extends \PHPUnit_Framework_TestCase
{
    protected $validProduct; //placeholder for product domain model which is valid
    protected $anotherValidProduct; //placeholder for product domain model which is valid
    protected $invalidProduct; //placeholder for product domain model which is invalid
    protected $outOfStockProduct; //placeholder for product domain model which is out of stock
    
    protected $validCouponValue; //placeholder for coupon domain model which is valid with type = value
    protected $validCouponPct; //placeholder for coupon domain model which is valid with type = percentage
    protected $invalidCoupon; //placeholder for coupon domain model which is invalid
    protected $outOfStockCoupon; //placeholder for coupon domain model which is out of stock
    protected $expiredCoupon; //placeholder for coupon domain model which is expired
    protected $hugeCoupon; //placeholder for coupon domain model which is valid and has a huge discount

    
    protected function setUp() {
        //create yesterday datetime
        $yesterdayDate= new \DateTime();
        $yesterdayDate->add(\DateInterval::createFromDateString('yesterday'));
        
        //create last week datetime
        $lastWeekDate = new \DateTime();
        $lastWeekDate->sub(new \DateInterval('P7D'));
        
        //create tommorow's date
        $tomorrowDate = new \DateTime();
        $tomorrowDate->add(new \DateInterval("P1D"));
        
        //initiate valid product
        $this->validProduct = new Product();
        $this->validProduct->setId('VALID01');
        $this->validProduct->setName('Valid Product');
        $this->validProduct->setDescription('Valid Product Desc');
        $this->validProduct->setPrice(100000);
        $this->validProduct->setStock(50);
        $this->validProduct->setStatus(Product::STATUS_ACTIVE);
        
        //initiate another valid product
        $this->anotherValidProduct = new Product();
        $this->anotherValidProduct->setId('VALID02');
        $this->anotherValidProduct->setName('Valid Product 2');
        $this->anotherValidProduct->setDescription('Valid Product Desc 2');
        $this->anotherValidProduct->setPrice(5000);
        $this->anotherValidProduct->setStock(100);
        $this->anotherValidProduct->setStatus(Product::STATUS_ACTIVE);
        
        //initiate invalid product
        $this->invalidProduct = new Product();
        $this->invalidProduct->setId('INVALID01');
        $this->invalidProduct->setName('Invalid Product');
        $this->invalidProduct->setDescription('Invalid Product Desc');
        $this->invalidProduct->setPrice(50000);
        $this->invalidProduct->setStock(5);
        $this->invalidProduct->setStatus(Product::STATUS_INACTIVE);
        
        //initiate out of stock product
        $this->outOfStockProduct = new Product();
        $this->outOfStockProduct->setId('OUT01');
        $this->outOfStockProduct->setName('No Stock Prod');
        $this->outOfStockProduct->setDescription('No Stock Desc');
        $this->outOfStockProduct->setPrice(50000);
        $this->outOfStockProduct->setStock(0);
        $this->outOfStockProduct->setStatus(Product::STATUS_ACTIVE);
        
        //initiate valid coupon (type: value)
        $this->validCouponValue = new Coupon();
        $this->validCouponValue->setId('VALIDC1');
        $this->validCouponValue->setValue(25000);
        $this->validCouponValue->setQuantity(1000);
        $this->validCouponValue->setStatus(Coupon::STATUS_VALID);
        $this->validCouponValue->setType(Coupon::TYPE_VALUE);
        $this->validCouponValue->setStartDate($yesterdayDate); //set valid from yesterday
        $this->validCouponValue->setExpiryDate($tomorrowDate); //set valid until tomorrow
        
        //initiate valid coupon (type: percentage)
        $this->validCouponPct = new Coupon();
        $this->validCouponPct->setId('VALIDC2');
        $this->validCouponPct->setValue(10); //10% discount
        $this->validCouponPct->setQuantity(5000);
        $this->validCouponPct->setStatus(Coupon::STATUS_VALID);
        $this->validCouponPct->setType(Coupon::TYPE_PERCENTAGE);
        $this->validCouponPct->setStartDate($yesterdayDate); //set valid from yesterday
        $this->validCouponPct->setExpiryDate($tomorrowDate); //set valid until tomorrow
        
        //initiate invalid coupon
        $this->invalidCoupon = new Coupon();
        $this->invalidCoupon->setId('INVALIDC1');
        $this->invalidCoupon->setValue(10000);
        $this->invalidCoupon->setQuantity(10);
        $this->invalidCoupon->setStatus(Coupon::STATUS_INVALID);
        $this->invalidCoupon->setType(Coupon::TYPE_VALUE);
        $this->invalidCoupon->setStartDate($yesterdayDate); //set valid from yesterday
        $this->invalidCoupon->setExpiryDate($tomorrowDate); //set valid until tomorrow
        
        //initiate expired coupon
        $this->expiredCoupon = new Coupon();
        $this->expiredCoupon->setId('EXPC1');
        $this->expiredCoupon->setValue(10000);
        $this->expiredCoupon->setQuantity(10);
        $this->expiredCoupon->setStatus(Coupon::STATUS_VALID);
        $this->expiredCoupon->setType(Coupon::TYPE_VALUE);
        $this->expiredCoupon->setStartDate($lastWeekDate); //valid from last week 
        $this->expiredCoupon->setExpiryDate($yesterdayDate); //valid until yesterday
        
        //initiate out of stock coupon
        $this->outOfStockCoupon = new Coupon();
        $this->outOfStockCoupon->setId('NOSTOCKC1');
        $this->outOfStockCoupon->setValue(50000);
        $this->outOfStockCoupon->setQuantity(0);
        $this->outOfStockCoupon->setStatus(Coupon::STATUS_VALID);
        $this->outOfStockCoupon->setType(Coupon::TYPE_VALUE);
        $this->outOfStockCoupon->setStartDate($lastWeekDate); //valid from last week 
        $this->outOfStockCoupon->setExpiryDate($tomorrowDate); //valid until tommorow
        
        //initiate valid coupon (type: value) with a huge discount
        $this->hugeCoupon = new Coupon();
        $this->hugeCoupon->setId('VALIDC3');
        $this->hugeCoupon->setValue(1000000); //1 million discount
        $this->hugeCoupon->setQuantity(10);
        $this->hugeCoupon->setStatus(Coupon::STATUS_VALID);
        $this->hugeCoupon->setType(Coupon::TYPE_VALUE);
        $this->hugeCoupon->setStartDate($yesterdayDate); //set valid from yesterday
        $this->hugeCoupon->setExpiryDate($tomorrowDate); //set valid until tomorrow
    }

    //test scenario of item addition, modification, deletion on order without coupon
    public function testAddAndModifyValidProductToOrderWithoutCoupon() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        
        //try adding valid item
        $newOrder->addItem($this->validProduct, 2);
        $items = $newOrder->getItems(); //should return array
        $this->assertInternalType('array', $items);
        $this->assertEquals(1, count($items));
        
        //check order totalAmount so far
        $this->assertEquals(200000, $newOrder->totalAmount);
        
        //check quantity of order item added just now
        $orderItem = current($items);
        $this->assertEquals(2, $orderItem->productQuantity);
        
        //test add a different product, and check items again
        $newOrder->addItem($this->anotherValidProduct, 4);
        $items = $newOrder->getItems();
        $this->assertEquals(2, count($items));
        
        //check order totalAmount so far
        $this->assertEquals(220000, $newOrder->totalAmount);
        
        //test add same product, and check quantity again
        $newOrder->addItem($this->validProduct, 3);
        $items = $newOrder->getItems();
        $this->assertEquals(2, count($items)); //should still be 2 items only
        
        //find that product added just now, check quantity
        $foundOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID01' == $item->product->id) {
                $foundOrderItem = $item;
                break;
            }
        }
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $foundOrderItem);
        $this->assertEquals(5, $foundOrderItem->productQuantity);
        
        //check order totalAmount so far
        $this->assertEquals(520000, $newOrder->totalAmount);
        
        //now try modifying order item
        $newOrder->editItem($this->anotherValidProduct, 10);
        
        //check order totalAmount so far
        $this->assertEquals(550000, $newOrder->totalAmount);
        
        //find that product modified just now, check quantity
        $foundOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID02' == $item->product->id) {
                $foundOrderItem = $item;
                break;
            }
        }
        $this->assertEquals(10, $foundOrderItem->productQuantity);
        
        //now try removing product
        $newOrder->removeItem($this->anotherValidProduct);
        $items = $newOrder->getItems();
        $this->assertEquals(1,count($items));
        
        //check order totalAmount so far
        $this->assertEquals(500000, $newOrder->totalAmount);
    }
    
    //test scenario of item addition, modification, deletion on order with coupon
    public function testAddAndModifyValidProductToOrderWithCoupon() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        
        //try adding valid item
        $newOrder->addItem($this->validProduct, 2);
        $items = $newOrder->getItems(); //should return array
        $this->assertInternalType('array', $items);
        $this->assertEquals(1, count($items));
        
        //check order totalAmount so far
        $this->assertEquals(200000, $newOrder->totalAmount);
        
        //check quantity of order item added just now
        $orderItem = current($items);
        $this->assertEquals(2, $orderItem->productQuantity);
        
        //now try applying valid coupon (10% discount)
        $newOrder->applyCoupon($this->validCouponPct);
        $this->assertEquals(2, $orderItem->productQuantity);
        //check total amount after discount
        $this->assertEquals(180000, $newOrder->totalAmount);
        
        //test add a different product, and check items again
        $newOrder->addItem($this->anotherValidProduct, 4);
        $items = $newOrder->getItems();
        $this->assertEquals(2, count($items));
        
        //check order totalAmount so far (after discount)
        $this->assertEquals(198000, $newOrder->totalAmount);
        
        //test add same product, and check quantity again
        $newOrder->addItem($this->validProduct, 3);
        $items = $newOrder->getItems();
        $this->assertEquals(2, count($items)); //should still be 2 items only
        
        //find that product added just now, check quantity
        $foundOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID01' == $item->product->id) {
                $foundOrderItem = $item;
                break;
            }
        }
        $this->assertInstanceOf('\Emoneygw\ValueObject\Concrete\OrderItem', $foundOrderItem);
        $this->assertEquals(5, $foundOrderItem->productQuantity);
        
        //check order totalAmount so far (after discount)
        $this->assertEquals(468000, $newOrder->totalAmount);
        
        //now try modifying order item
        $newOrder->editItem($this->anotherValidProduct, 10);
        
        //check order totalAmount so far (after discount)
        $this->assertEquals(495000, $newOrder->totalAmount);
        
        //find that product modified just now, check quantity
        $foundOrderItem = false;
        foreach($newOrder->getItems() as $item) {
            if('VALID02' == $item->product->id) {
                $foundOrderItem = $item;
                break;
            }
        }
        $this->assertEquals(10, $foundOrderItem->productQuantity);
        
        //now try removing product
        $newOrder->removeItem($this->anotherValidProduct);
        $items = $newOrder->getItems();
        $this->assertEquals(1,count($items));
        
        //check order totalAmount so far (after discount)
        $this->assertEquals(450000, $newOrder->totalAmount);
    }
    
    public function testAddInvalidProductToOrder() {
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        
        //try adding invalid product        
        $this->expectException(\DomainException::class);
        $newOrder->addItem($this->invalidProduct, 2);
    }
    
    public function testAddOutOfStockProductToOrder() {
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        
        //try adding product        
        $this->expectException(\DomainException::class);
        $newOrder->addItem($this->outOfStockProduct, 2);
    }
    
    public function testApplyCouponToEmptyOrder() {
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        
        //try adding coupon to order with no items
        $this->expectException(\DomainException::class);
        $newOrder->applyCoupon($this->validCouponPct);
    }
    
    public function testApplyInvalidCouponToOrder() {
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        $newOrder->addItem($this->validProduct, 2);
        
        //try adding invalid coupon
        $this->expectException(\DomainException::class);
        $newOrder->applyCoupon($this->invalidCoupon);
    }
    
    public function testApplyOutOfStockCouponToOrder() {
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        $newOrder->addItem($this->validProduct, 2);
        
        //try adding out of stock coupon 
        $this->expectException(\DomainException::class);
        $newOrder->applyCoupon($this->outOfStockCoupon);
    }
    
    public function testApplyExpiredCouponToOrder() {
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        $newOrder->addItem($this->validProduct, 2);
        
        //try adding out of stock coupon 
        $this->expectException(\DomainException::class);
        $newOrder->applyCoupon($this->expiredCoupon);
    }
    
    public function testApplyHugeDiscountCouponToOrder() {
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        
        //try adding valid item
        $newOrder->addItem($this->validProduct, 2);
        //check order totalAmount so far
        $this->assertEquals(200000, $newOrder->totalAmount);
        
        //add a different product, and check again
        $newOrder->addItem($this->anotherValidProduct, 4);
        //check order totalAmount so far
        $this->assertEquals(220000, $newOrder->totalAmount);
        
        $this->expectException(\DomainException::class);
        //now try applying huge discount coupon (1 mil discount)
        $newOrder->applyCoupon($this->hugeCoupon);
    }
}
