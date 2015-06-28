<?php
/**
 * Getter/setter trait
 *
 * @author li <>
 * @version 1.0
 */
namespace vendor\TagSlicer;

trait GetSetTrait {
    /**
     * Getter
     *
	 * @param string $key
     * @return mixed value or false
     */	
	public function get($key)
	{
		if (isset($this->$key)) {
			return $this->$key;
		} else {
			return false;
		}
	}
    /**
     * Setter
     *
	 * @param string $key
	 * @param string $value
     * @return void
     */		
	public function set($key, $value)
	{
		$this->$key = $value;
	}	
}
