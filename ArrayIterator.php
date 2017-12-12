<?php

/**
 * @copyright Copyright (c) 2012, Andrew Fisher
 * @author Andrew Fisher
 */

class AndFisher_ArrayIterator extends RecursiveArrayIterator
{

	public $recyclable = null;
	
	/**
	 * @function setRecyclable()
	 * @desc Set an object to be recycled and returned bu the iterator
	 * @access public
	 * @param mixed $recyclable 
	 * @return void
	 */
	
	public function setRecyclable($recyclable)
	{
		$this->recyclable = $recyclable;
	}
	
	
	/**
	 * @function current()
	 * @desc Overload the parent method to use our recyclable obhect
	 * @access public
	 * @return mixed
	 */
	
	public function current()
	{
		return $this->_map(parent::current());
	}
	
	
	/**
	 * @function offsetGet()
	 * @desc Overload the parent method to use our recyclable object
	 * @access public
	 * @param mixed $index
	 * @return mixed
	 */
	
	public function offsetGet($index)
	{
		return $this->_map(parent::offsetGet($index));
	}
	
	
	/**
	 * @function _map()
	 * @desc Do the leg work of mapping the iteration item to our recyclable object
	 * @access protected
	 * @param mixed $item
	 * @return mixed
	 */
	
	protected function _map($item)
	{
		foreach ($item AS $attr => $val) {
			$this->recyclable->$attr = $val;
		}
		return $this->recyclable;
	}
}
