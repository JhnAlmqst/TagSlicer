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
     * @var string base path.
     */
	public $basePath;
    /**
     * @var PDO
     */
    static public $db;
    /**
     * @var table in DB
     */
    static public $table;
    /**
     * @var search types
     */
    static public $types;	
    /**
     * @var object Router
     */
    public $router;	
	
	public function __construct($config) {
		$this->basePath = $config['basePath'];
	
		self::$db = new Connection($config['db']);
		self::$table = $config['db']['table'];
		$this->router = new Router($config['route']);
		$this->sitevars = $config['sitevars'];
		self::$types = $config['types'];
	}
	
	public function run() {
		$className = "\\controllers\\" . ucfirst($this->router->get('controller'));
		$actionName = "action" . ucfirst($this->router->get('action'));

		if (!is_file($this->basePath . $className . '.php')) {
			$className = '\controllers\Error';
			$this->router->set('controller', 'error');
		}

		$controller = new $className($this);

		if (!method_exists($controller, $actionName)) {
			$actionName = 'actionShow';
			$this->router->set('action', 'show');
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