<?php
/**
 * Emoneygw\Test\OrderAfterProcessScenarioTest
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Scenario test involving what happened to order after successful processing: 
 * - submitting payment proof
 * - adding shipping info to order
 * - updating order shipping info
 */
namespace Emoneygw\Test;

use ApplicationTest\Bootstrap;
use PHPUnit_Framework_TestCase;

use Emoneygw\Model\Concrete\Order;
use Emoneygw\ValueObject\Concrete\OrderItem;
use Emoneygw\Model\Concrete\Product;
use Emoneygw\Model\Concrete\Coupon;

class OrderAfterProcessScenarioTest extends \PHPUnit_Framework_TestCase
{
    public function testValidPaymentProofSubmission() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_APPROVED);
        
        //try submitting payment proof
        $newOrder->submitPaymentProof('dummyproof');
        
        //check order status
        $this->assertEquals(Order::STATUS_ON_PROCESS, $newOrder->status);
    }
    
    public function testInvalidPaymentProofSubmission() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DELIVERED);
        
        $this->expectException(\DomainException::class);
        //try submitting payment proof
        $newOrder->submitPaymentProof('dummyproof');
        
        //check order status, status should stay the same
        $this->assertEquals(Order::STATUS_DELIVERED, $newOrder->status);
    }
    
    public function testValidShippingInfoAddition() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_ON_PROCESS);
        //shipping status should still be not ready
        $this->assertEquals(Order::SHIP_STATUS_NOT_READY, $newOrder->shippingStatus);
        //shipping id should still be empty
        $this->assertEmpty($newOrder->shippingId);
        
        //try adding shipping info
        $newOrder->addShippingInfo('ship123');
        
        //check order status and shipping status
        $this->assertEquals(Order::STATUS_ON_DELIVERY, $newOrder->status);
        $this->assertEquals(Order::SHIP_STATUS_ON_PROCESS, $newOrder->shippingStatus);
        $this->assertEquals('ship123', $newOrder->shippingId); //should have a value now
    }
    
    public function testInvalidShippingInfoAddition() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_DRAFT);
        //shipping status should still be not ready
        $this->assertEquals(Order::SHIP_STATUS_NOT_READY, $newOrder->shippingStatus);
        //shipping id should still be empty
        $this->assertEmpty($newOrder->shippingId);
        
        $this->expectException(\DomainException::class);
        //try adding shipping info
        $newOrder->addShippingInfo('ship999');
        
        //check order status and shipping status, there should be no change
        $this->assertEquals(Order::STATUS_DRAFT, $newOrder->status);
        $this->assertEquals(Order::SHIP_STATUS_NOT_READY, $newOrder->shippingStatus);
        $this->assertEmpty($newOrder->shippingId); //should still be empty
    }
    
    public function testValidShippingInfoUpdate() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_ON_DELIVERY);
        $newOrder->setShippingStatus(Order::SHIP_STATUS_ON_PROCESS);
        $newOrder->setShippingId('shipME');
        
        //try updating shipping info
        $newOrder->updateShippingInfo('updatedSHIP123', Order::SHIP_STATUS_DELIVERED);
        
        //check order status and shipping status
        $this->assertEquals(Order::SHIP_STATUS_DELIVERED, $newOrder->shippingStatus);
        $this->assertEquals(Order::STATUS_DELIVERED, $newOrder->status);
        $this->assertEquals('updatedSHIP123', $newOrder->shippingId); //should have a value now
    }
    
    public function testOtherValidShippingInfoUpdate() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_ON_DELIVERY);
        $newOrder->setShippingStatus(Order::SHIP_STATUS_ON_PROCESS);
        $newOrder->setShippingId('shipMETOO');
        
        //try updating shipping info
        $newOrder->updateShippingInfo('updatedSHIPME', Order::SHIP_STATUS_ON_PROCESS);
        
        //check order status and shipping status
        $this->assertEquals(Order::SHIP_STATUS_ON_PROCESS, $newOrder->shippingStatus); //ther should be no change
        $this->assertEquals(Order::STATUS_ON_DELIVERY, $newOrder->status); //there should be no change for status
        $this->assertEquals('updatedSHIPME', $newOrder->shippingId); //should have a different value now
    }
    
    public function testInvalidShippingInfoUpdate() {
        //setup order
        $newOrder = new Order();
        $newOrder->setStatus(Order::STATUS_REJECTED);
        $newOrder->setShippingStatus(Order::SHIP_STATUS_NOT_READY);
        
        //shipping id should still be empty
        $this->assertEmpty($newOrder->shippingId);
        
        $this->expectException(\DomainException::class);
        //try updating shipping info
        $newOrder->updateShippingInfo('updatedSHIP123', Order::SHIP_STATUS_DELIVERED);
        
        //check order status and shipping status
        $this->assertEquals(Order::SHIP_STATUS_NOT_READY, $newOrder->shippingStatus);
        $this->assertEquals(Order::STATUS_REJECTED, $newOrder->status);
        $this->assertEmpty($newOrder->shippingId); //should still be empty
    }
}