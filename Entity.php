<?php

/**
 * @copyright Copyright (c) 2013, Andrew Fisher
 * @author Andrew Fisher
 */

class Model_Entity
{
	protected $data = array();
	
	protected $references = array();
	
	protected $entityMap = array();
	
	protected $objects = array();
	
	public function __construct(array $data = null, array $references = null)
	{
		if (is_array($data)) {
			
			foreach ($data AS $key => $value) {
				$this->$key = $value;
			}
			
		}
	}
	
	public function exists()
	{
		return !empty($this->id);
	}
	
	public function toArray()
	{
		return $this->data;
	}
	
	public function fromArray(array $array)
	{
		foreach ($array AS $k => $v) {
			$this->$k = $v;
		}
		return $this;
	}
	
	public function setReferenceId($name, $id)
	{
		$this->references[$name] = $id;
	}
	
	public function getReferenceId($name)
	{
		if (isset($this->references[$name])) {
			return $this->references[$name];
		}
	}
	
	
	public function __set($name, $value)
	{
		if (! array_key_exists($name, $this->data)) {
            
			if (substr($name, 0, 2) == '__' && strpos($name, '.')) {
				
				list($key, $attr) = explode('.', substr($name, 2), 2);
				
				if (array_key_exists($key, $this->entityMap)) {	
				
                    if (! isset($this->objects[$key])) {
						$this->objects[$key] = new $this->entityMap[$key];
					}
                  
                    if (is_subclass_of($this->objects[$key], 'Model_EntityList')) {
                        
                        $this->objects[$key]->clear()->addItems($value);
                        return;
                    
                    } else {
                        
                        $this->objects[$key]->$attr = $value;
                        return;
                    
                    }
				}
				
			}
			
			throw new Exception('You cannot set new properties on this object');
		
		} else {
			$this->data[$name] = $value;
		}
	}
	
	public function __get($name)
	{
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}
		
		if (array_key_exists($name, $this->objects)) {
			return $this->objects[$name];
		}
	}
	
	public function __isset($name)
	{
		return isset($this->data[$name]);
	}
	
	public function __unset($name)
	{
		if (isset($this->data[$name])) {
			unset($this->data[$name]);
		}
	}
    
    
    public function __call($method, $arguments)
    {
        
        $key = strtolower(substr($method, 3));
        
        if (substr($method, 0, 3) == 'set' && array_key_exists($key, $this->entityMap)) {
            
            list($entity) = $arguments;
            
            $clone = isset($arguments[1]) ? $arguments[1] : true;
            
            if (is_a($entity, $this->entityMap[$key])) {
                
                if ($clone)
                    $this->objects[$key] = clone $entity;
                else
                    $this->objects[$key] = $entity;
            }
        }
    }
}