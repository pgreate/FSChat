<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.11.2021
 * Time: 23:33
 */



use FSChat\Api\Protocol\JsonResponse;
require('../../inc/bootstrap_api.php');

$routerName = 'FSChat\\Api\Router\\'.ucfirst($entity).'Router';
if(class_exists($routerName)){

    $router = new $routerName();

    $result = $router->dispatch($segments);

    echo (new JsonResponse())->data($result)->respond();
    exit;

}else{
    echo (new JsonResponse)->error("Invalid Route. Invalid entity: ".$entity)->respond();
    exit;
}