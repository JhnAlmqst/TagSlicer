<?php
/**
 * Bootstrap file
 *
 * @author li <>
 * @version 1.0
 */
namespace vendor\TagSlicer;
use controllers;

class Application
{
    /**
     * @var search types
     */
    static public $types;	

    /**
     * @var object DI
     */
    private $di;		
	
	public function __construct($config)
	{
		$this->di = new DI();
		$this->di->router = new Router($config['route']);
		$this->di->db = new Connection($config['db']);
		$this->di->basePath = $config['basePath'];
		$this->di->siteVars = $config['siteVars'];
		$this->di->types = $config['types'];
	}
	
	public function run()
	{
		$className = "\\controllers\\" . ucfirst($this->di->router->get('controller'));
		$actionName = "action" . ucfirst($this->di->router->get('action'));

		if (!is_file($this->di->basePath . $className . '.php')) {
			$className = '\controllers\Error';
			$this->di->router->set('controller', 'error');
		}

		$controller = new $className($this->di);

		if (!method_exists($controller, $actionName)) {
			$actionName = 'actionShow';
			$this->di->router->set('action', 'show');
		}
		
		$controller->$actionName();
	}	

    /**
     * Class autoload loader.
     *
     * @param string $className
     * @return void
     */	
    public static function autoload($className)
    {
		if (strpos($className, 'Twig') === false) {
			$className = str_replace(array('.', '?', '/', ':'), '', $className);
            $classFile = str_replace('\\', '/', $className) . '.php';
			$path = dirname($_SERVER['DOCUMENT_ROOT']) . '/';

            if ($classFile === false || !is_file($path.$classFile)) {
                return;
            }
        } else {
            return;
        }

        include_once($path.$classFile);
    }	
}

spl_autoload_register(['vendor\TagSlicer\Application', 'autoload'], true, true);