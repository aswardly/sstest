<?php
/**
 * Emoneygw\DataMapper\Hydrator\OrderItem
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Specific hydrator for OrderItem domain model
 */
namespace Emoneygw\DataMapper\Hydrator;

use Emoneygw\DataMapper\Hydrator\AbstractHydrator;
use Emoneygw\ValueObject\Concrete\OrderItem as Model;
use Emoneygw\Model\Concrete\Product as ProductModel;
use Emoneygw\DataMapper\Concrete\Product as ProductMapper;

class OrderItem extends AbstractHydrator
{
    /**
     * Product mapper
     * @var \Emoneygw\DataMapper\Concrete\Product
     */
    protected $_productMapper = null;
    
    /**
     * Constructor
     * @param Emoneygw\DataMapper\Concrete\Product $mapper data mapper for order item
     */
    public function __construct(ProductMapper $mapper) {
        parent::__construct();
        $this->_productMapper = $mapper;
    }
    
    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, $object) {
        //runtime type check
        if(false === $object instanceof Model)
            throw new \InvalidArgumentException('Invalid model type');
        
        //remove product from data
        $productId = $data['product_id'];
        unset($data['product_id']);
        
        $object = parent::hydrate($data, $object);
        
        //load product and set to hydrated object
        $this->_productMapper->resetFilter();
        $this->_productMapper->resetOrder();

        $this->_productMapper->filterById($productId);
        $product = $this->_productMapper->findByFilter(); //NOTE: this should result in only 1 product model object in an array
        
        $productObj = current($product);
        if($productObj instanceof ProductModel) {
            $object->product = $productObj;
        }
        
        //set flag on hydrated object
        $object->setLoadedFromStorage($this->_loadedFromStorage);
        
        //take snapshot
        if(true === $this->_autoSnapshotAfterHydration) {
            $object->takeSnapshot();
        }
        return $object;
    }
}