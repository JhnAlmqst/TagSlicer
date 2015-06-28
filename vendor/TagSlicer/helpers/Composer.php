<?php
/**
 * @copyright li
 * @license 
 */
namespace vendor\TagSlicer\helpers;

use Composer\Script\Event;
/**
 * Copy Bootstrap and JQuery files from vendor to public directory
 *
 * @author li <>
 * @version 1.0
 */
class Composer
{
    /**
     * Copy Bootstrap and JQuery files in public directory
     * after Composer install or update
     *
     * @return void
     */
	public static function postUpdate(Event $event)
	{
		$assets = [
			'bootstrap.min.css' => [
				'/../../../vendor/twitter/bootstrap/dist/css/',
				'/../../../www/css/bootstrap/'
			],			
			'bootstrap-theme.min.css' => [
				'/../../../vendor/twitter/bootstrap/dist/css/',
				'/../../../www/css/bootstrap/'
			],
			'bootstrap.min.js' => [
				'/../../../vendor/twitter/bootstrap/dist/js/',
				'/../../../www/js/bootstrap/'
			],
			'jquery.min.js' => [
				'/../../../vendor/components/jquery/',
				'/../../../www/js/jquery/'
			]
		];
		
		foreach ($assets as $assetName => $assetDirs) {
			if (!is_dir(__DIR__ .$assetDirs[1])) {
				try {
					if (!mkdir(__DIR__ .$assetDirs[1], 0777)) {
						throw new \Exception('Can\'t create directory '.substr($assetDirs[1], 9));
					}
				} catch (\Exception $e) {
					echo $e->getMessage();
				}
			}

			try {
				if (!copy(__DIR__ .$assetDirs[0].$assetName, __DIR__ .$assetDirs[1].$assetName)) {
					throw new \Exception('Can\'t copy file '.substr($assetDirs[0], 9).$assetName."\n");
				}
			} catch (\Exception $e) {
				echo $e->getMessage();
			}			
		}		
	}
}
