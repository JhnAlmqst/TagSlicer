<?php

return [
	'basePath' => dirname(__DIR__),
    'db' => [
		'dsn' => 'mysql:host=localhost;dbname=search',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8',
		'table'	=> 'search_results'
	],
	'route' => [
		'defaultController' => 'index',
		'defaultAction' => 'index',
		'defaultLayout' => 'main',
	],
	'siteVars' => [
		'title'	=> 'TagSlicer',
		'elements_on_page' => 10
	],
	'types' => [
		['id' => 1, 'title' => 'ссылки', 'regexp' => '/(<a.*?\/a>)/'],
		['id' => 2, 'title' => 'изображения', 'regexp' => '/(<img.*?>)/'],
		['id' => 3, 'title' => 'текст', 'regexp' => function($text) { return '/'.preg_quote($text, '/').'/'; }],
		//['id' => 4, 'title' => 'bold', 'regexp' => '/(<strong.*?\/strong>)/'],
	]
];
