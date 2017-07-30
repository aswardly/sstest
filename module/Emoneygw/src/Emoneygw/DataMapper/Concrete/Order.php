<?php
/**
 * Emoneygw\DataMapper\Concrete\Order
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Specific data mapper for Order domain model
 */
namespace Emoneygw\DataMapper\Concrete;

use Emoneygw\DataMapper\AbstractMapper;
use Emoneygw\ValueObject\ValueObjectInterface;
use Emoneygw\Model\Concrete\Order as ConcreteModel;
use Emoneygw\Model\Concrete\Coupon as CouponModel;
use Emoneygw\DataMapper\Hydrator\Order as ModelHydrator;
use Zend\Db\Adapter\AdapterInterface;
use Emoneygw\DataMapper\Concrete\OrderItem as DetailMapper;
use Emoneygw\DataMapper\Concrete\Coupon as CouponMapper;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;

class Order extends AbstractMapper
{
    /**
     * Specific datamapper for order item
     * @var \Emoneygw\DataMapper\Concrete\OrderItem
     */
    protected $_detailMapper = null;
    
    /**
     * Specific datamapper for coupon
     * @var \Emoneygw\DataMapper\Concrete\Coupon
     */
    protected $_couponMapper = null;
    
    /**
     * Constructor
     * 
     * @param \Zend\Db\Adapter\AdapterInterface $dbAdapter database adapter to set
     * @param string $tableName name of table handled by this datamapper
     * @param \Emoneygw\DataMapper\Hydrator\Coupon $hydrator hydrator object to set
     * @param \Emoneygw\Model\Concrete\Product $modelPrototype model prototype object to set
     * @param array $nameMapping name mapping to set
     * @param \Emoneygw\DataMapper\Concrete\OrderItem $detailMapper order item data mapper
     * @param \Emoneygw\DataMapper\Concrete\Coupon $couponMapper coupon data mapper
     */
    public function __construct(AdapterInterface $dbAdapter, $tableName, ModelHydrator $hydrator, ConcreteModel $modelPrototype, $nameMapping, DetailMapper $detailMapper, CouponMapper $couponMapper) {
        parent::__construct($dbAdapter, $tableName, $hydrator, $modelPrototype, $nameMapping);
        $this->_detailMapper = $detailMapper;
        $this->_couponMapper = $couponMapper;
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
            
        $detailItems = $model->items; //items should return an array

        $data = $this->_hydrator->extract($model);
        
        try {
            //begin db transaction
            $this->_dbAdapter->getDriver()->getConnection()->beginTransaction();
        
            $action = new Insert($this->_tableName);
            $action->values($data);

            $sql = new Sql($this->_dbAdapter);
            $statement = $sql->prepareStatementForSqlObject($action);
            $statement->execute();
            
            //get last inserted id value;
            $this->_lastInsertedId = $this->_dbAdapter->getDriver()->getLastGeneratedValue();
            
            $this->_detailMapper->setParentReferenceId($model->id);
            
            foreach($detailItems as $detailObject) {
                $this->_detailMapper->insert($detailObject);
            }
        } catch (\Exception $e) {
            $this->_dbAdapter->getDriver()->getConnection()->rollback(); //rollback transaction
            //TODO: log exception and other special behaviour here
            throw new \RuntimeException('Saving operation error: '.$e->getMessage());
        }
        
        //commit transaction
        $this->_dbAdapter->getDriver()->getConnection()->commit();
            
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
            $detailItems = $model->items; //items should return an array
            $deletedItems = $model->getDeletedItems();
            $couponObj = $model->coupon;
            
            $data = $this->_hydrator->extract($model);

            try {
                //begin db transaction
                $this->_dbAdapter->getDriver()->getConnection()->beginTransaction();
            
                $action = new Update($this->_tableName);
                $action->set($data);
                //NOTE: by convention, all name mapping should contain index 'id'
                $action->where(array($this->_nameMapping['id'].' =?' => $model->id));

                $sql = new Sql($this->_dbAdapter);
                $statement = $sql->prepareStatementForSqlObject($action);
                $result = $statement->execute();
                
                $this->_detailMapper->setParentReferenceId($model->id);
                //loop over the items, if any existing item was updated, the update will be persisted here
                //if any new items was added, the item will be persisted here
                foreach($detailItems as $detailObject) {
                    if($detailObject->isLoadedFromStorage()) {//detail object was loaded from storage, meaning it exists in storage, do an update
                        if($detailObject->isModified()) {
                            $this->_detailMapper->update($detailObject);
                        }
                        //else detailObject was no modified, do nothing
                    }
                    else { //detail object was not loaded from storage, do an insert
                        $this->_detailMapper->insert($detailObject);
                    }
                }
                //delete removed items (if any)
                foreach($deletedItems as $deleted) {
                    $this->_detailMapper->delete($deleted);
                }
                
                //save coupon info if it was updated
                if($couponObj instanceof CouponModel) {
                    if($couponObj->isModified()) {
                        $this->_couponMapper->update($couponObj);
                    }
                }
                
            } catch (\Exception $e) {
                $this->_dbAdapter->getDriver()->getConnection()->rollback(); //rollback transaction
                //TODO: log exception and other special behaviour here
                throw new \RuntimeException('Update operation error: '.$e->getMessage());
            }
            
            //commit transaction
            $this->_dbAdapter->getDriver()->getConnection()->commit();
        
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
    
    /**
     * Deletes existing record
     * Returns true if deletion is successful
     * 
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model domain model object to persist
     * @return boolean
     */
    public function delete(ValueObjectInterface $model) {
        //runtime type check
        if(false === $model instanceof ConcreteModel) {
            throw new \InvalidArgumentException("Model objet type is invalid");
        }
        
        try {
            //NOTE: For efficiency, just perform Sql delete here for all related records in detail table, then delete the record in master table
            //but this can be considered hardcoding since the table name of the detail table is hardcoded here
            
            //begin db transaction
            $this->_dbAdapter->getDriver()->getConnection()->beginTransaction();
            
            //delete record in detail table
            $detailAction = new Delete('T_ORDER_ITEM'); //NOTE: Hardcoded table name
            //NOTE: by convention, all name mapping should contain index 'id'
            $detailAction->where(array($this->_nameMapping['id'].' = ?' => $model->getId())); //$namemapping['id'] should contain 'order_id'
            
            $detailSql = new Sql($this->_dbAdapter);
            $detailStatement = $detailSql->prepareStatementForSqlObject($detailAction);
            $$detailResult = $detailStatement->execute();
            
            //delete record in master table
            $action = new Delete($this->_tableName);
            //NOTE: by convention, all name mapping should contain index 'id'
            $action->where(array($this->_nameMapping['id'].' = ?' => $model->getId()));
            
            $sql = new Sql($this->_dbAdapter);
            $statement = $sql->prepareStatementForSqlObject($action);
            $result = $statement->execute();
        } catch (\Exception $e) {
            $this->_dbAdapter->getDriver()->getConnection()->rollback(); //rollback transaction
            //TODO: log exception and other special behaviour here
            throw new \RuntimeException('Delete operation error: '.$e->getMessage());
        }
        
        //commit transaction
        $this->_dbAdapter->getDriver()->getConnection()->commit();
        
        return (bool)$result->getAffectedRows();
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
    
    /**
     * Filter by id
     * @param string $value value used for filtering
     */
    public function filterByShippingId($value) {
        $this->_filter->equalTo($this->_nameMapping['shippingId'], $value);
    }
    
    //NOTE: other custom filter (for sql query where condition) for this datamapper can be added here
}