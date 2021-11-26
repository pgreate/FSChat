<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.11.2021
 * Time: 20:42
 */

namespace FSChat\Api\Router;


use FSChat\ServiceProvider;
use MongoDB\Collection;
use ZMQ;
use ZMQContext;

class ChatManagerRouter implements RouterInterface
{

    public function dispatch($arguments)
    {
        //Handle auth request
        if(empty($arguments[0])){
            session_start();
            return session_id();
        }

        /**
         * @var Collection $collection
         */
        $collection = ServiceProvider::get('db')->messages;

        if($arguments[0] == 'list'){
//            var_dump($arguments);exit;
            $result = $collection->aggregate([
                ['$group'=>[
                    '_id'=>'$sid',
                    'totalMessages'=>['$sum'=>1],
                ]],

            ]);
            return $result->toArray();
        }

        //Handle messages history request
        if($_SERVER['REQUEST_METHOD'] == 'GET'){

            $result = $collection->find(['sid'=>$arguments[0]]);
            return $result->toArray();

            //Handle send message request
        }elseif($_SERVER['REQUEST_METHOD'] == 'POST'){

            $new_message = [
                'sid' => $arguments[0],
                'text' => file_get_contents('php://input'),
                'manager' => 1,
            ];
            $result = $collection->insertOne($new_message);

            //push to zmq
            $zmq_request = array_merge($new_message,[
                'type' => 'message',
            ]);

            $zmq_context = new ZMQContext();
            $zmq_socket = $zmq_context->getSocket(ZMQ::SOCKET_PUSH,CHAT_CHANNEL_PREFIX);

            $zmq_socket->connect(ZMQ_URL);
            $zmq_socket->send(json_encode($zmq_request));
            $zmq_socket->disconnect(ZMQ_URL);

            return (string)$result->getInsertedId();

        }

        return false;
    }
}