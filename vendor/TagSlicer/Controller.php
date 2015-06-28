<?php
/**
 * Parent class for Controllers
 *
 * @author li <>
 * @version 1.0
 */
namespace vendor\TagSlicer;

abstract class Controller
{
	use GetSetTrait;
    /**
     * @var string base path.
     */
	public $basePath;
    /**
     * @var object Router
     */
    public $router;
    /**
     * @var array site variables
     */
    public $sitevars;	
    /**
     * @var string page title
     */
    public $title;	
	
	public function __construct($app)
	{
		$this->basePath = $app->basePath;
		$this->router = $app->router;
		$this->sitevars = $app->sitevars;
	}
	
	protected function render($fileName, $vars)
	{
		require_once $this->basePath.'/vendor/twig/twig/lib/Twig/Autoloader.php';
		
		\Twig_Autoloader::register();
		
		$loader = new \Twig_Loader_Filesystem($this->basePath.'/views');
		$twig = new \Twig_Environment($loader, ['autoescape' => false]);
		
		$view = $twig->loadTemplate($this->router->get('controller').'/'.$fileName.'.php');
		$layout = $twig->loadTemplate('layouts/main.php');

		echo $layout->render([
			'content' => $view->render($vars),
			'title' => $this->get('title'),
			'sitename' => $this->sitevars['title'],
			'controller' => $this->router->get('controller'),
		]);
	}
	
	protected function returnAjax($status, $data)
	{
		echo json_encode([
			'status' => $status,
			'content' => $data
		]);
		die;
	}
	
	protected function returnError($msg = 'Такой страницы не существует')
	{
		$this->router->set('controller', 'error');
		$this->set('title', 'Ошибка!');
		$this->render('show', [
			'page_title' => $this->get('title'),
			'content' => $msg,
		]);	
	}	
}