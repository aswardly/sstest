<?php
/**
 * Emoneygw\DataMapper\CollectionMapperInterface
 * 
 * @author Andi Wijaya
 * @version 1
 * Interface for collection data mapper objects
 * only used for fectching collection of a domain model
 */
namespace Emoneygw\DataMapper;

interface CollectionMapperInterface
{
    /**
     * Find all records (no filter condition)
     * 
     * returns \IteratorAggregate
     */
    public function findAll();
    
    /*
     * Find all records (using filter condition)
     * 
     * returns \IteratorAggregate
     */
    public function findByFilter();
    
    /**
     * Reset filter
     */
    public function resetFilter();
    
    /**
     * Reset order
     */
    public function resetOrder();
    
}