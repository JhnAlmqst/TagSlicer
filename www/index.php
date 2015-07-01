<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/TagSlicer/Application.php');

$config = require(__DIR__ . '/../config/config.php');
$app = new vendor\TagSlicer\Application($config);

$app->run();
