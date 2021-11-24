<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.11.2021
 * Time: 2:35
 */

error_reporting(E_ALL);
ini_set('display_errors',1);

require('../../vendor/autoload.php');
require('config.php');

$db = require('db.php');
\FSChat\ServiceProvider::register('db',$db->{MONGO_BASE});

$prefix = dirname($_SERVER['SCRIPT_NAME']);
$route = str_replace($prefix,'',$_SERVER["REQUEST_URI"]);
$route = ltrim($route,'/');


$segments = explode('/',$route);
$entity = !empty($segments[0]) ? $segments[0] : 'chat';
array_shift($segments);