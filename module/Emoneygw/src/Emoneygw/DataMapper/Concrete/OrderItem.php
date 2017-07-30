<?php
/**
 * Emoneygw\DataMapper\Concrete\OrderItem
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Specific data mapper for Order Item domain model
 */
namespace Emoneygw\DataMapper\Concrete;

use Emoneygw\DataMapper\AbstractMapper;
use Emoneygw\DataMapper\Concrete\Product as ProductMapper;
use Emoneygw\ValueObject\Concrete\OrderItem as ConcreteModel;
use Emoneygw\DataMapper\Hydrator\OrderItem as ModelHydrator;
use Emoneygw\ValueObject\ValueObjectInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;

class OrderItem extends AbstractMapper
{
    /**
     * Reference id to set when saving a model object (master detail relationship)
     * @var string
     */
    private $_parentReferenceId = null;
    
    /**
     * Product data mapper
     * @var \Emoneygw\DataMapper\Concrete\Product
     */
    private $_productMapper;
    /**
     * Constructor
     * 
     * @param \Zend\Db\Adapter\AdapterInterface $dbAdapter database adapter to set
     * @param string $tableName name of table handled by this datamapper
     * @param \Emoneygw\DataMapper\Hydrator\Coupon $hydrator hydrator object to set
     * @param \Emoneygw\Model\Concrete\Product $modelPrototype model prototype object to set
     * @param array $nameMapping name mapping to set
     * @param \Emoneygw\DataMapper\Concrete\Product $productMapper product data mapper to set
     */
    public function __construct(AdapterInterface $dbAdapter, $tableName, ModelHydrator $hydrator, ConcreteModel $modelPrototype, $nameMapping, ProductMapper $productMapper) {
        parent::__construct($dbAdapter, $tableName, $hydrator, $modelPrototype, $nameMapping);
        $this->_productMapper = $productMapper;
    }
    
    /**
     * Set parent reference value
     * @param type $value
     */
    public function setParentReferenceId($value) {
        $this->_parentReferenceId = $value;           
    }
    
    /**
     * Inserts a new record
     * Returns true if saving is successful
     * 
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model domain model object to persist
     * @return boolean
     */
    public function insert(ValueObjectInterface $model) {
        //runtime type check
        if(false === $model instanceof ConcreteModel) {
            throw new \InvalidArgumentException("Model objet type is invalid");
        }
        
        $data = $this->_hydrator->extract($model);
        
        //set parent reference id value
        $data['order_id'] = $this->_parentReferenceId; 
        
        try {
            $action = new Insert($this->_tableName);
            $action->values($data);

            $sql = new Sql($this->_dbAdapter);
            $statement = $sql->prepareStatementForSqlObject($action);
            $statement->execute();          
            //get last inserted id value;
            $this->_lastInsertedId = $this->_dbAdapter->getDriver()->getLastGeneratedValue();
        } catch (\Exception $e) {
            //TODO: log exception and other special behaviour here
            throw new \RuntimeException('Saving operation error: '.$e->getMessage());
        }
        
        //after saving of domain model object is successful, set flag in hydrator:
        //- load from storage = true, since model was written to storage
        //- snapshot after hydration = true, since domain model was just now persisted
        $model->takeSnapshot();
        $model->setLoadedFromStorage(true);
        
        //saving record successful
        return true;
    }
   
   /**
     * Updates existing record
     * Returns true if update is successful
     * 
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model domain model object to persist
     * @return boolean
     */
    public function update(ValueObjectInterface $model) {
        //runtime type check
        if(false === $model instanceof ConcreteModel) {
            throw new \InvalidArgumentException("Model objet type is invalid");
        }
        
        if($model->isModified()) {
            $data = $this->_hydrator->extract($model);
            
            //set parent reference id value
            $data['order_id'] = $this->_parentReferenceId;
        
            try {
                $action = new Update($this->_tableName);
                $action->set($data);
                //NOTE: by convention, all name mapping should contain index 'id'
                $action->where(array($this->_nameMapping['id'].' =?' => $model->id));

                $sql = new Sql($this->_dbAdapter);
                $statement = $sql->prepareStatementForSqlObject($action);
                $result = $statement->execute();
                
                //update product
                if($model->isModified()) {
                    $this->_productMapper->update($model->product);
                }
                
            } catch (\Exception $e) {
                //TODO: log exception and other special behaviour here
                throw new \RuntimeException('Update operation error: '.$e->getMessage());
            }
            
            //after saving of domain model object is successful, set flag in hydrator:
            //- load from storage = true, since model was written to storage
            //- snapshot after hydration = true, since domain model was just now persisted
            $model->takeSnapshot();
            $model->setLoadedFromStorage(true);
            
            return (bool)$result->getAffectedRows();
        }
        //else model was not modified, no need to persist
        return true;
    }
    
    //==============================================================================
    // Custom filter for this domain data mapper
    
    /**
     * Filter by product id
     * @param string $value value used for filtering
     */
    public function filterByProductId($value) {
        $this->_filter->equalTo($this->_nameMapping['product'], $value);
    }
    
    /**
     * Filter by order id
     * @param string $value value used for filtering
     */
    public function filterByOrderId($value) {
        $this->_filter->equalTo('order_id', $value);
    }
    
    //NOTE: other custom filter (for sql query where condition) for this datamapper can be added here
}