<?php
/**
 * Elements controller
 *
 * @author li <>
 * @version 1.0
 */
namespace controllers;
use vendor\TagSlicer\Controller;
use vendor\TagSlicer\Application;
use models\Search;

class Elements extends Controller 
{	
	public function actionIndex()
	{
		$elements = new Search();
		$elements_on_page = $this->get('sitevars');
		$elements_on_page = $elements_on_page['elements_on_page'];
		
		list($out, $count) = $elements->findAll($this->router, $elements_on_page);
		$count = ceil($count / $elements_on_page);
		
		if ($this->router->get('id') > $count) {
			$this->returnError();			
		} else {
			$this->set('title', 'Список проверенных сайтов');
			$types = [];
			foreach (Application::$types as $type) {
				$types[$type['id']] = $type['title'];
			}

			$this->render('index', [
				'page_title' => $this->get('title'),
				'elements' => $out,
				'types' => $types,
				'count' => 1 >= $count ? false : range(1, $count),
				'current' => !$this->router->get('id') ? 1 : $this->router->get('id')
			]);
		}
	}
	
	public function actionPage()
	{
		return $this->actionIndex();
	}	
	
	public function actionShow()
	{
		$element = new Search();
		
		$out = $element->find(['id' => $this->router->get('id')]);

		if ($out) {
			$values = explode("\t", htmlspecialchars_decode($out['elements'], ENT_QUOTES));
			$this->set('title', 'Найденные значения');
			$this->render('show', [
				'page_title' => $this->get('title'),
				'url' => $out['url'],
				'count' => $out['count'],
				'values' => $values,
			]);
		} else {
			$this->returnError();
		}
	}	
}