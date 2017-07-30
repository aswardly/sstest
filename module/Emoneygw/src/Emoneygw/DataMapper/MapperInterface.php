<?php
/**
 * Emoneygw\DataMapper\MapperInterface
 * 
 * @author Andi Wijaya
 * @version 1
 * Interface for data mapper objects
 */
namespace Emoneygw\DataMapper;

use Emoneygw\ValueObject\ValueObjectInterface;

interface MapperInterface
{
    /**
     * Find record by id
     * returns false if record is not found
     * 
     * @param mixed $id
     * @return mixed
     */
    public function findById($id);
    
    /**
     * Save a new model object (persist to data storage)
     * 
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model model object to save in storage
     */
    public function insert(ValueObjectInterface $model);
    
    /**
     * updates model object (save changes to persist in data storage)
     * 
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model model object to update in storage
     */
    public function update(ValueObjectInterface $model);
    
    /**
     * Deletes a model from data storage
     * 
     * @param \Emoneygw\ValueObject\ValueObjectInterface $model model object to delete in storage
     */
     public function delete(ValueObjectInterface $model);
     
}