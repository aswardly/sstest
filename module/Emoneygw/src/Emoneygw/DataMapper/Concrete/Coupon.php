<?php
/**
 * Emoneygw\DataMapper\Concrete\Coupon
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Specific data mapper for Coupon domain model
 */
namespace Emoneygw\DataMapper\Concrete;

use Emoneygw\DataMapper\AbstractMapper;
use Emoneygw\Model\Concrete\Coupon as ConcreteModel;
use Emoneygw\DataMapper\Hydrator\Coupon as ModelHydrator;
use Zend\Db\Adapter\AdapterInterface;

class Coupon extends AbstractMapper
{
    /**
     * Constructor
     * 
     * @param \Zend\Db\Adapter\AdapterInterface $dbAdapter database adapter to set
     * @param string $tableName name of table handled by this datamapper
     * @param \Emoneygw\DataMapper\Hydrator\Coupon $hydrator hydrator object to set
     * @param \Emoneygw\Model\Concrete\Product $modelPrototype model prototype object to set
     * @param array $nameMapping name mapping to set
     */
    public function __construct(AdapterInterface $dbAdapter, $tableName, ModelHydrator $hydrator, ConcreteModel $modelPrototype, $nameMapping) {
        parent::__construct($dbAdapter, $tableName, $hydrator, $modelPrototype, $nameMapping);
    }
    
    //==============================================================================
    // Custom filter for this domain data mapper
    
    /**
     * Filter by id
     * @param string $value value used for filtering
     */
    public function filterById($value) {
        $this->_filter->equalTo($this->_nameMapping['id'], $value);
    }
    
    /**
     * Filter by id (using sql like)
     * @param string $value value used for filtering
     */
    public function filterByIdLike($value) {
        $this->_filter->like($this->_nameMapping['id'], '%'.$value.'%');
    }
    
    //NOTE: other custom filter (for sql query where condition) for this datamapper can be added here
}