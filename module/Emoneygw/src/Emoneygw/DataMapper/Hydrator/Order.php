<?php
/**
 * Emoneygw\DataMapper\Hydrator\Order
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Specific hydrator for Order domain model
 * 
 */
namespace Emoneygw\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\AbstractHydrator;
use Emoneygw\DataMapper\Concrete\OrderItem as OrderItemMapper;
use Emoneygw\DataMapper\Concrete\Coupon as CouponMapper;
use Emoneygw\Model\Concrete\Coupon as CouponModel;
use Emoneygw\Model\Concrete\Order as Model;

class Order extends AbstractHydrator
{
    /**
     * Order item datamapper
     * @var \Emoneygw\DataMapper\Concrete\OrderItem
     */
    protected $_itemMapper = null;
    
    /**
     * Coupon datamapper
     * @var \Emoneygw\DataMapper\Concrete\Coupon
     */
    protected $_couponMapper = null;
    
    /**
     * Constructor
     * @param \Emoneygw\DataMapper\Concrete\OrderItem $itemMapper data mapper for order item
     * @param \Emoneygw\DataMapper\Concrete\Coupon $couponMapper data mapper for coupon
     */
    public function __construct(OrderItemMapper $itemMapper, CouponMapper $couponMapper) {
        parent::__construct();
        $this->_itemMapper = $itemMapper;
        $this->_couponMapper = $couponMapper;
    }
    
    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, $object) {
        //runtime type check
        if(false === $object instanceof Model) {
            throw new \InvalidArgumentException('invalid type of object for hydration');
        }
        
        //remove coupon from data
        $couponId = $data['order_coupon'];
        unset($data['order_coupon']);
        
        $object = parent::hydrate($data, $object);
        
        //refill coupon with model object
        $couponObj = $this->_couponMapper->findById($couponId);
        if($couponObj instanceof CouponModel) { //only set coupon if it is found
            $object->coupon = $couponObj;
        }
                
        //load order items and set to hydrated object
        $this->_itemMapper->resetFilter();
        $this->_itemMapper->resetOrder();

        $this->_itemMapper->filterByOrderId($object->id);
        $object->items = $this->_itemMapper->findByFilter();
        
        //set flag on hydrated object
        $object->setLoadedFromStorage($this->_loadedFromStorage);
        
        //take snapshot
        if(true === $this->_autoSnapshotAfterHydration) {
            $object->takeSnapshot();
        }
        return $object;
    }
}