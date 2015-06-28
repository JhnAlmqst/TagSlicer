<?php
/**
 * $_POST
 *
 * @author li <>
 * @version 1.0
 */
namespace vendor\TagSlicer;

class PostRequest
{
	use GetSetTrait;

	public function __construct()
	{
		foreach ($_POST as $key => $value) {
			$this->$key = $this->clean($value);
		}
	}

    /**
     * Clean post-data.
     *
     * @param mixed $value
     * @return mixed cleaned data
     */	
	private function clean($value)
	{
		if ( is_array($value) ) {
			foreach ($value as $k => $v) {
				$value[$k] = $this->clean($v);
			}
			return $value;
		} else {
			return htmlspecialchars(trim(str_replace(["\r", "\n"], '', $value)), ENT_QUOTES);
		}
	}	
}
