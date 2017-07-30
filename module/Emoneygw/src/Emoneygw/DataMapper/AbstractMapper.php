<?php
/**
 * Emoneygw\DataMapper\AbstractMapper
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Data mapper for domain model
 * uses hydrator object (Zend\Stdlib\Hydrator) for converting between database record (associative array format) and domain model object
 * Maps record from 1 db table to 1 type of domain model
 * 
 * A Note about database transactions:
 * Begin transaction, commit, and rollback actions can be directly performed in datamapper by getting the database connection instance (Zend\Db\Adapter\Driver\ConnectionInterface) and calling the corresponding methods.
 * Zend\Db\Adapter\Driver\ConnectionInterface instance is able to simulate nested transactions using counters.
 * In the case of multiple datamappers using the same database connection, when a datamapper executes a begin transaction statement, then if another datamapper executes another begin transaction statement, no error will occur
 * Only the outermost transaction statement is the real transaction sent to DBMS, all other nested transactions are internally managed by the ConnectionInterface instance
 */
namespace Emoneygw\DataMapper;

use Emoneygw\DataMapper\Hydrator\AbstractHydrator;
use Emoneygw\DataMapper\MapperInterface;
use Emoneygw\DataMapper\CollectionMapperInterface;
use Emoneygw\ValueObject\ValueObjectInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Stdlib\ArrayUtils;

abstract class AbstractMapper implements MapperInterface, CollectionMapperInterface
{
    /**
     * database adapter object
     * @var \Zend\Db\Adapter\AdapterInterface;
     */
    protected $_dbAdapter;
    
    /**
     * Name of table handled by this datamapper
     * @var String
     */
    protected $_tableName;
    
    /**
     * Last inserted id of database operation (such as inserting to a table with an autoincrement primary key)
     * @var mixed
     */
    private $_lastInsertedId;
    
    /**
     * hydrator for transforming from database result set to model object and vice versa
     * @var \Emoneygw\DataMapper\Hydrator\AbstractHydrator
     */
    protected $_hydrator;
    
    /**
     * model object used for prototype injection to hydrator
     * @var \Emoneygw\ValueObject\ValueObjectInterface
     */
    protected $_modelPrototype;
    
    /**
     * Zend\Db\Sql\Where filter used for filtering collection
     * @var Where 
     */
    protected $_filter = null;
    
    /**
     * Limit number placeholder to be set to Zend\Db\Sql\Select object
     * defaults to null (should be interpreted as no limit set on Zend\Db\Sql\Select object)
     * @var integer
     */
    protected $_limit = null;
    
    /**
     * array containing mapping of property names of model/value objects to db field name
     * array keys should contain field/property name from a corresponding model/value object, array values should contain corresponding db field name
     * this mapping is used for: 
     * - validity lookup when determining whether a field value can be used for sorting or not
     * - fetching db column names for composing sql
     * @var array
     */
    protected $_nameMapping = array();
    
    /**
     * array of sorting conditions
     * array keys should contain field name to sort, array values should contain sorting direction (ASC or DESC)
     * @var array
     */
    protected $_order = array();
    
    /**
     * Constructor
     * 
     * @param \Zend\Db\Adapter\AdapterInterface $dbAdapter database adapter to set
     * @param string $tableName name of table handled by this datamapper
     * @param \Emoneygw\DataMapper\Hydrator\AbstractHydrator $hydrator hydrator object to set
     * @param \Emoneygw\ValueObject\ValueObjectInterface $modelPrototype model prototype object to set
     * @param array $nameMapping name mapping to set
     */
    public function __construct(AdapterInterface $dbAdapter, $tableName, AbstractHydrator $hydrator, ValueObjectInterface $modelPrototype, $nameMapping) {
        $this->_dbAdapter = $dbAdapter;
        $this->_tableName = $tableName;
        $this->setHydrator($hydrator);
        $this->setModelPrototype($modelPrototype);
        $this->setNameMapping($nameMapping);
        
        //initialize sql where condition
        $this->_filter = new Where();
    }
    
    /**
     * get hydrator of this datamapper
     * useful for setting options to the hydrator
     * @return AbstractHydrator
     */
    public function getHydrator() {
       return $this->_hydrator; 
    }
    
    /**
     * Set hydrator for this datamapper
     * @param \Emoneygw\DataMapper\Hydrator\AbstractHydrator $hydrator Hydrator object to set
     */
    protected function setHydrator(AbstractHydrator $hydrator) {
        $this->_hydrator = $hydrator;
    }
    
    /**
     * Set model prototype for this datamapper
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model model prototype to set
     */
    protected function setModelPrototype(ValueObjectInterface $model) {
        $this->_modelPrototype = $model;
    }
    
    /**
     * set name mapping
     * @param array $mapping
     */
    public function setNameMapping(array $mapping) {
        $this->_nameMapping = $mapping;
    }
    
    /**
     * Shortcut function for saving model to storage
     * Action performed is determined by flag "isLodedFromStorage" value
     * 
     * @param Emoneygw\ValueObject\ValueObjectInterface $model
     * @return mixed
     */
    public function save(ValueObjectInterface $model) {
        if($model->isLoadedFromStorage()) {
            $result = $this->update($model);
        }
        else {
            $result = $this->insert($model);
        }
        return $result;
    }
       
    /**
     * Set limit value
     * @param integer $limit
     */
    public function setLimit(integer $limit) {
        $this->_limit = $limit;
    }
    
    /**
     * Reset limit
     */
    public function resetLimit() {
        $this->_limit = null;
    }
    
    /**
     * Reset filter
     */
    public function resetFilter() {
        $this->_filter = new Where();
    }
    
    /**
     * Reset order
     */
    public function resetOrder() {
        $this->_order = array();
    }
    
    /**
     * Add order condition
     * @param $field field name for sorting
     * @param $order sort order, defaults to 'ASC'
     * @throws \InvalidArgumentException
     */
    public function orderBy($field, $order = Select::ORDER_ASCENDING) {
        if(false === $this->isValidForOrder($field))
            throw new \InvalidArgumentException('Unknown field value for ordering: '.$field);
        
        if(Select::ORDER_ASCENDING != strtoupper($order) && Select::ORDER_DESCENDING != strtoupper($order))
            throw new \InvalidArgumentException("Invalid order direction value");
        
        $this->_order[$this->_nameMapping[$field]] = strtoupper($order);
    }
    
    /**
     * Check whether a given field name value is valid for field order condition
     * @param string $field field name to check
     * @return boolean
     */
    public function isValidForOrder($field) {
        return in_array($field, array_keys($this->_nameMapping));
    }
    
    /**
     * Executes a query to find all records in table (no query search conditions)
     * Returns empty array if record is not found
     * 
     * @return array
     */
    public function findAll() {
        
        $sql = new Sql($this->_dbAdapter);
        $select = $sql->select($this->_tableName);
        //columns to fetch from table
        $select->columns(array_values($this->_nameMapping));
        
        //process order
        foreach($this->_order as $key=>$val) {
            $select->order($key." ".$val);
        }
        
        //process limit 
        if(false === is_null($this->_limit)) {
            $select->limit($this->_limit);
        }
        
        $this->resetFilter();
        $this->resetLimit();
        $this->resetOrder();
        
        $resultSet = new HydratingResultSet($this->_hydrator, $this->_modelPrototype);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        //\Zend\Debug\Debug::dump($statement); //for dumping executed query
        
        $result = $statement->execute();
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            //prior to hydrating from db result to domain model object, set flag in hydrator:
            //- load from storage = true, since we just indeed load from storage
            //- snapshot after hydration = true, since we want the domainn model object to take it's own snapshot after hydration
            $this->_hydrator->setLoadedFromStorage(true);
            $this->_hydrator->setAutoSnapshotAfterHydration(true);

            $resultSet = $resultSet->initialize($result);
            return ArrayUtils::iteratorToArray($resultSet, false);
        }
        //else an error has occured
        throw new \RuntimeException("Database result error");
    }
    
    /**
     * Executes a query to find all records in table (with query search conditions)
     * Returns empty array if record is not found
     * 
     * @return array
     */
    public function findByFilter() {
        
        $sql = new Sql($this->_dbAdapter);
        $select = $sql->select($this->_tableName);
        //columns to fetch from table
        $select->columns(array_values($this->_nameMapping));
        
        $select->where($this->_filter);
        
        //process order
        foreach($this->_order as $key=>$val) {
            $select->order($key." ".$val);
        }
        
        //process limit 
        if(false === is_null($this->_limit)) {
            $select->limit($this->_limit);
        }
        
        $this->resetFilter();
        $this->resetLimit();
        $this->resetOrder();
        
        $resultSet = new HydratingResultSet($this->_hydrator, $this->_modelPrototype);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        //\Zend\Debug\Debug::dump($statement);
        $result = $statement->execute();
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            //prior to hydrating from db result to domain model object, set flag in hydrator:
            //- load from storage = true, since we just indeed load from storage
            //- snapshot after hydration = true, since we want the domainn model object to take it's own snapshot after hydration
            $this->_hydrator->setLoadedFromStorage(true);
            $this->_hydrator->setAutoSnapshotAfterHydration(true);

            $resultSet = $resultSet->initialize($result);
            return ArrayUtils::iteratorToArray($resultSet, false);
        }
        //else an error has occured
        throw new \RuntimeException("Database result error");
    }
    
    /**
     * Find record by id
     * Returns false if record is not found
     * 
     * @return boolean|\Emoneygw\ValueObject\ValueObjectInterface
     */
    public function findById($id) {
        $sql = new Sql($this->_dbAdapter);
        $select = $sql->select($this->_tableName);
        //columns to fetch from table
        $select->columns(array_values($this->_nameMapping));
        //NOTE: by convention, all name mapping should contain index 'id'
        $select->where(array($this->_nameMapping['id'].' = ?' => $id));

        $this->_statement = $sql->prepareStatementForSqlObject($select);
        $result = $this->_statement->execute();
        
        if($result instanceof ResultInterface && $result->isQueryResult()) {
            $current = $result->current();//will return false if resultset is empty
            
            if(false === $current) {
                return false;
            }
            //prior to hydrating from db result to domain model object, set flag in hydrator:
            //- load from storage = true, since we just indeed load from storage
            //- snapshot after hydration = true, since we want the domainn model object to take it's own snapshot after hydration
            $this->_hydrator->setLoadedFromStorage(true);
            $this->_hydrator->setAutoSnapshotAfterHydration(true);
            
            return $this->_hydrator->hydrate($current, clone $this->_modelPrototype); //always clone the prototype to always return a new object every time
        }
        //else error occurred
        throw new \RuntimeException("Database result error");
    }
    
    /**
     * Inserts a new record
     * Returns true if saving is successful
     * 
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model domain model object to persist
     * @return boolean
     */
    public function insert(ValueObjectInterface $model) {
        $data = $this->_hydrator->extract($model);
        
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
        if($model->isModified()) {
            $data = $this->_hydrator->extract($model);

            try {
                $action = new Update($this->_tableName);
                $action->set($data);
                //NOTE: by convention, all name mapping should contain index 'id'
                $action->where(array($this->_nameMapping['id'].' =?' => $model->id));

                $sql = new Sql($this->_dbAdapter);
                $statement = $sql->prepareStatementForSqlObject($action);
                $result = $statement->execute();
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
    
   /**
     * Deletes existing record
     * Returns true if deletion is successful
     * 
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model domain model object to persist
     * @return boolean
     */
    public function delete(ValueObjectInterface $model) {
        $action = new Delete($this->_tableName);
        //NOTE: by convention, all name mapping should contain index 'id'
        $action->where(array($this->_nameMapping['id'].' = ?' => $model->id));
        try {
            $sql = new Sql($this->_dbAdapter);
            $statement = $sql->prepareStatementForSqlObject($action);
            $result = $statement->execute();
        } catch (\Exception $e) {
            //TODO: log exception and other special behaviour here
            throw new \RuntimeException('Delete operation error: '.$e->getMessage());
        }
        
        return (bool)$result->getAffectedRows();
    }
}