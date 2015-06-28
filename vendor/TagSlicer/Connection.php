<?php
/**
 * PDO
 *
 * @author li <>
 * @version 1.0
 */
namespace vendor\TagSlicer;

class Connection extends \PDO
{
	public function __construct($config)
	{
		try {
			parent::__construct(
						$config['dsn'],
						$config['username'],
						$config['password'],
						[\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \''.$config['charset'].'\'']
					); 
			$this->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );	
		} catch (PDOException $e) {
			die( 'Подключение не удалось: ' . $e->getMessage() );
		}
	}
}
