<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.11.2021
 * Time: 0:12
 */

namespace FSChat\Api\Router;


interface RouterInterface
{
    public function dispatch($arguments);
}