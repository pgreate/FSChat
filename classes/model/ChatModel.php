<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.11.2021
 * Time: 0:26
 */

namespace FSChat\Api\Router;

class ChatModel
{
    protected $id;

    /**
     * ChatModel constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }


    public function getMessages(){

    }
}