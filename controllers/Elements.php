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
		$elements = new Search($this->di->db);
		$elements_on_page = $this->di->siteVars;
		$elements_on_page = $elements_on_page['elements_on_page'];
		
		list($out, $count) = $elements->findAll($this->di->router, $elements_on_page);
		$count = ceil($count / $elements_on_page);
		
		$id = $this->di->router->get('id');
		if ($id > $count) {
			$this->returnError();			
		} else {
			$this->set('title', 'Список проверенных сайтов');
			$types = [];
			foreach ($this->di->types as $type) {
				$types[$type['id']] = $type['title'];
			}

			$this->render('index', [
				'page_title' => $this->get('title'),
				'elements' => $out,
				'types' => $types,
				'count' => 1 >= $count ? false : range(1, $count),
				'current' => !$id ? 1 : $id
			]);
		}
	}
	
	public function actionPage()
	{
		return $this->actionIndex();
	}	
	
	public function actionShow()
	{
		$element = new Search($this->di->db);
		
		$out = $element->find(['id' => $this->di->router->get('id')]);

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