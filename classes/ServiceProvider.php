<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.11.2021
 * Time: 1:06
 */

namespace FSChat;


class ServiceProvider
{
    protected static $services = [];


    public static function register($key,$instance){
        static::$services[$key] = $instance;
    }

    public static function get($key){
        if(isset(static::$services[$key])){
            return static::$services[$key];
        }
        return false;
    }
}