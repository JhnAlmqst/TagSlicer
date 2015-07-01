<?php
/**
 * Index controller
 *
 * @author li <>
 * @version 1.0
 */
namespace controllers;

use vendor\TagSlicer\Controller;
use vendor\TagSlicer\Application;

class Index extends Controller
{	
	public function actionIndex()
	{
		$this->set('title', 'Форма');
		$this->render('index', [
			'page_title' => $this->get('title'),
			'types' => $this->di->types,
			'content' => '',
		]);
	}
}