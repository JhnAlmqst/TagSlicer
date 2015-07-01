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
     * @var string page title
     */
    private $title;
    /**
     * @var object template engine
     */	
	private $template;
    /**
     * @var object DI
     */	
	protected $di;	
	
	public function __construct($di)
	{
		$this->set('di', $di);
		$this->set('template', $this->setTemplateEngine());
	}
	
	protected function render($fileName, $vars)
	{	
		list($layout, $view) = $this->getTemplates($fileName);

		if (!$layout || !$view) {
			return $this->returnError('Указан несуществующий шаблон');
		}

		echo $layout->render([
			'content' => $view->render($vars),
			'title' => $this->get('title'),
			'sitename' => $this->di->siteVars['title'],
			'controller' => $this->di->router->get('controller'),
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
		$this->di->router->set('controller', 'error');
		$this->set('title', 'Ошибка!');
		$this->render('show', [
			'page_title' => $this->get('title'),
			'content' => $msg,
		]);	
	}
    /**
	 * Template engine loader
	 *
     * @return void
     */	
	private function setTemplateEngine()
	{
		require_once $this->di->basePath.'/vendor/twig/twig/lib/Twig/Autoloader.php';
		
		\Twig_Autoloader::register();

		return new \Twig_Environment(
								new \Twig_Loader_Filesystem($this->di->basePath.'/views'),
								['autoescape' => false]
							);
	}
    /**
	 * Get objects for view and layout
	 *
	 * @param string name of view file
     * @return array [object layout template, object view template]
     */	
	private function getTemplates($fileName)
	{
		$layoutFile = 'layouts/'.$this->di->router->get('layout').'.php';
		$viewFile = $this->di->router->get('controller').'/'.$fileName.'.php';

		return [
			is_file($this->di->basePath .'/views/'. $layoutFile) ? $this->template->loadTemplate($layoutFile) : false,
			is_file($this->di->basePath .'/views/'. $viewFile) ? $this->template->loadTemplate($viewFile) : false
		];
	}	
}