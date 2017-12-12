<?php

/**
 * @copyright Copyright (c) 2013, Andrew Fisher
 * @author Andrew Fisher
 */

class Model_EntityList implements IteratorAggregate, Countable
{
	protected $entityClass;
	protected $entity;
	protected $items = array();
	protected $references = array();
	protected $objects = array();
	protected $iterator = null;
	
	public function __construct($data = null)
	{
		$this->entity = new $this->entityClass;
		
		if (null != $data) {
            $this->addItems($data);
		}
	}
    
    
    /**
     * @function addItems()
     * @desc Publicly add items to the list.
     * @access public
     * @param array $data
     * @return void
     */
    
    public function addItems($data)
    {
        foreach ($data AS $item) {
            $this->items[] = $item;
        }
    }
	
    
    /**
     * @function clear()
     * @desc Need a method for clearing existing list items
     * @access public
     * @return Model_EntityList
     */
    
    public function clear()
    {
        $this->items = array();
        # @todo
        # Is there a better way to reset the iterator?
        $this->iterator = null;
        return $this;
    }
    
    
	public function reverse()
	{
		foreach (array('items', 'references', 'objects') AS $array) {
			$this->$array = array_reverse($this->$array);
		}
		
		return $this;
	}
	
	public function toArray()
	{
		return $this->items;
	}
	
	public function getLength()
	{
		return count($this->items);
	}
	 
    public function getIterator()
    {
		if (is_null($this->iterator)) {
			
			$this->iterator = new AndFisher_ArrayIterator($this->items);
			$this->iterator->setRecyclable($this->entity);
		}
        return $this->iterator;
    }
 
    public function count()
    {
         return sizeof($this->items);
    }
	
	public function getItemAt($i)
	{
		if ($i >= $this->getLength()) {
			throw new Exception('Index out of range: '.$i);
		}
		
		if (isset($this->objects[$i]) && $this->objects[$i] instanceof $this->entityClass)
			return $this->objects[$i];
		
		if ($this->items[$i] instanceof $this->entityClass)
			return $this->items[$i];
		
		return new $this->entityClass($this->items[$i]);
	}
    
    
    /**
     * @function getEntity()
     * @desc Getter for this list's entity object.
     * @access public
     * @return Model_Entity
     */
    
    public function getEntity()
    {
        return $this->entity;
    }
	
}