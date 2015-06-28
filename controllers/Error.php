<?php
/**
 * Error controller
 *
 * @author li <>
 * @version 1.0
 */
namespace controllers;

use vendor\TagSlicer\Controller;

class Error extends Controller 
{	
	public function actionShow()
	{
		$this->set('title', 'Ошибка!');
		$this->render('show', [
			'page_title' => $this->get('title'),
			'content' => 'Такой страницы не существует',
		]);
	}
}