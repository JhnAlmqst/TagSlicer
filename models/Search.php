<?php
/**
 * Search db model
 *
 * @author li <>
 * @version 1.0
 */
namespace models;
use vendor\TagSlicer\GetSetTrait;

class Search
{	
	use GetSetTrait;
    /**
	 * Description of table fields
	 *
     * @var array
     */	
	private $fields = [
		'id' => '`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT',
		'url' => '`url` varchar(255) NOT NULL',
		'type' => '`type` TINYINT NOT NULL',
		'elements' => '`elements` text NOT NULL',
		'count' => '`count` smallint(6) NOT NULL',
	];
	private $db;

	public function __construct($db)
	{
		$this->set('db', $db);
		$table_exists = false;
		foreach ($this->db->query('SHOW TABLES') as $r) {
			if ($r[0] == $this->db->getTable()) {
				$table_exists = true;
			}
		}
		
		if (!$table_exists) {
			$this->create();
		}
	}
	
	public function save()
	{
		$insertSet = '';
		foreach ($this->fields as $field => $v) {
			$insertSet .= '`'.$field. '` = :'.$field.',';
		}
		$sth = $this->db->prepare('
					INSERT
						INTO '.$this->db->getTable().'
						SET '.substr($insertSet, 0, -1).'
				');
		foreach ($this->fields as $field => $v) {
			$sth->bindValue(':'.$field, htmlspecialchars($this->get($field), ENT_QUOTES));
		}
		$sth->execute();
		return $this->db->lastInsertId();
	}
	
	public function findAll(\vendor\TagSlicer\Router $router, $elements_on_page)
	{
		$res = $this->db->query('
					SELECT COUNT(*)
						FROM '.$this->db->getTable().'
				');
		$count = $res->fetchColumn();

		$from = intval($router->get('id'));
		if (0 != $from) {
			$from -= 1;
		}

		$res = $this->db->query('
					SELECT id, url, type, count
						FROM '.$this->db->getTable().'
						ORDER BY id
						LIMIT '.($from * $elements_on_page).', '.$elements_on_page.'
				');

		return [$res->fetchAll(\PDO::FETCH_ASSOC), $count];
	}
    /**
     * Find in table
     *
	 * @param array $params params for WHERE expr
     * @return array
     */		
	public function find($params = [])
	{
		$data = ['fields' => [], 'values' => []];
		foreach ($params as $field => $value) {
			if (isset($this->fields[$field])) {
				$data['fields'][] = ' `'.$field.'` = ? ';
				$data['values'][] = $value;
			}
		}
		$sth = $this->db->prepare('
					SELECT *
						FROM '.$this->db->getTable().'
						WHERE '.join('AND', $data['fields']).'
				');
		$sth->execute($data['values']);
		return $sth->fetch(\PDO::FETCH_ASSOC);
	}	

    /**
     * Create table if it don't exists
     *
     * @return void
     */		
	private function create()
	{
		$q = '
			CREATE TABLE `'.$this->db->getTable().'` (
				'.join(',', $this->fields).',
			  PRIMARY KEY (`id`),
			  KEY `url` (`url`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
		';
		$res = $this->db->query($q);
	}	
}