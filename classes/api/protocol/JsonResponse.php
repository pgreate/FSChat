<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.11.2021
 * Time: 23:57
 */
namespace FSChat\Api\Protocol;

class JsonResponse
{
    protected $data;
    protected $errors = [];


    public function error($text){
        $this->errors[] = $text;
        return $this;
    }

    public function data($data){
        $this->data = $data;
        return $this;
    }


    public function respond(){
        $response = new \stdClass();

        if(!empty($this->errors)){
            $response->errors = $this->errors;
        }else{
            $response->data = $this->data;
        }

        //todo: headers

        return json_encode($response);
    }
}