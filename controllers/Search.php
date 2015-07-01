<?php
/**
 * Search controller
 *
 * @author li <>
 * @version 1.0
 */
namespace controllers;

use vendor\TagSlicer\Controller;
use models\SearchForm;

class Search extends Controller 
{	
	public function actionIndex()
	{
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$form = new SearchForm();

			if (!$form->validate()) {
				$this->returnAjax(0, $form->post());			
			}
			list($status, $message) = $form->getSearchResult($this->di->db, $this->di->types);
			$this->returnAjax($status, $message);
		} else {
			$this->returnError();		
		}
	}
}