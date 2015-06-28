<?php
/**
 * Router
 *
 * @author li <>
 * @version 1.0
 */
namespace vendor\TagSlicer;

class Router
{
	use GetSetTrait;
    /**
     * @var string current controller
     */
	private $controller;
    /**
     * @var string current action
     */	
	private $action;

	public function __construct($config)
	{
		foreach ($_GET as $key => $value) {
			$this->$key = htmlspecialchars(trim($value), ENT_QUOTES);
		}
		
		if (!isset($this->controller)) {
			$this->controller = $config['defaultController'];
		}
		if (!isset($this->action)) {
			$this->action = $config['defaultAction'];
		}
	}	
}
