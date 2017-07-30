<?php
/**
 * Emoneygw\Model\ModelInterface
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * Interface for domain model objects
 * domain model object is considered as an entity, which must have identity
 */

namespace Emoneygw\Model;

use Emoneygw\ValueObject\ValueObjectInterface;

interface ModelInterface extends ValueObjectInterface
{
    /**
     * get id of this model
     * 
     * @return mixed
     */
    public function getId();
    
    /**
     * sets id of this model
     * 
     * @param mixed $id id of this model
     */
    public function setId($id);
}