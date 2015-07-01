<?php
/**
 * Search form model
 *
 * @author li <>
 * @version 1.0
 */
namespace models;
use vendor\TagSlicer\PostRequest;

class SearchForm
{	
    /**
     * @var PostRequest
     */
	private $post;
	private $data = [
		'link'	=>	[
			'validate'	=>	['empty']
		],
		'type'	=>	[
			'validate'	=>	['empty', 'is_numeric']
		],
		'text'	=>	[
			'validate'	=>	[['empty', 'type', '3']]
		]
	];

	public function __construct()
	{
		$this->post = new PostRequest();
	}
	
	public function validate()
	{
		$valide = true;
		foreach ($this->data as $key => $opts) {
			if (false === $this->post->get($key)) {
				$valide = false;
			} elseif (isset($opts['validate'])) {
				foreach ($opts['validate'] as $test) {
					// if validate condition isn't array
					if (!is_array($test)) {
						if (!$this->testing($key, $test)) {
							$this->post->set($key, null);
							$valide = false;
						}
					} else {
					// if validate condition is array, then 
					// (0) - function, (1) - post field on which our field depends,
					// (2) - value of field (1) that starts testing
						if ($test[2] == $this->post->get($test[1])) {
							if (!$this->testing($key, $test[0])) {
								$this->post->set($key, null);
								$valide = false;
							}
						} else {
							$this->post->set($key, '');
						}
					}
				}
			}
		}
		return $valide;
	}
    /**
     * Validation
     *
     * @param string post field name
     * @param string validation function name
     * @return boolean
     */		
	private function testing($key, $test)
	{
		if ('empty' == $test) {
			$key = $this->post->get($key);
			return !empty($key);
		} else {
			return $test($this->post->get($key));
		}		
	}
    /**
     * Return post fields in case of error
     *
     * @return array
     */		
	public function post()
	{
		$ret = [];
		foreach($this->post as $key => $value) {
			$ret[$key] = $value;
		}
		return $ret;
	}
	public function getSearchResult($db, $types)
	{
		$record = new Search($db);

		$link = $this->post->get('link');
		$type = (int)$this->post->get('type');
		$text = $this->post->get('text');

		if (strpos($link, 'http') === false) {
			$link = 'http://'. $link;
		}
		$regexp = [];
		foreach ($types as $t) {
			$regexp[$t['id']] = is_callable($t['regexp']) ? $t['regexp']($text) : $t['regexp'];
		}	
		
/* 			1 => '/(<a.*?\/a>)/',
			2 => '/(<img.*?>)/',
			3 => '/'.preg_quote($text, '/').'/', */


		if ($element = $record->find(['type' => $type, 'url' => $link])) {
			return [1, "На этой странице уже производился поиск: <a href=\"/elements/show/".$element['id']."/\">перейти</a>"];
		}
		if (!isset($regexp[$type])) {
			return [-1, 'Недействительный тип поиска'];
		}
		if (!$siteSrc = $this->getSearchData($link)) {
			return [-1, 'Неверный адрес сайта'];
		}
		
		preg_match_all($regexp[$type], $siteSrc, $match);	

		if (0 == count($match[0])) {
			return [-1, 'Не было найдено ни одного совпадения'];
		}
		
		$record->set('url', $link);
		$record->set('type', $type);
		$record->set('elements', join("\t", $match[0]));
		$record->set('count', count($match[0]));
		if ($id = $record->save()) {
			return [1, "Было найдено совпадений: ". count($match[0]) ." (<a href=\"/elements/show/".$id."/\">перейти</a>)"];
		}
		
		return [0, 'Произошла ошибка'];
	}
    /**
     * Get html source of site
     *
	 * @param string URL
     * @return string
     */		
	private function getSearchData($link)
	{
		$out = file_get_contents($link);
		return $out;
	}
}