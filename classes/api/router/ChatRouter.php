<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.11.2021
 * Time: 23:55
 */

namespace FSChat\Api\Router;


use FSChat\ServiceProvider;
use ZMQ;
use ZMQContext;

class ChatRouter implements RouterInterface
{

    /**
     * ChatRouter constructor.
     */
    public function __construct()
    {

    }

    public function dispatch($arguments)
    {
        //Handle auth request
        if(empty($arguments[0])){
            session_start();
            return session_id();
        }


        $collection = ServiceProvider::get('db')->messages;

        //Handle messages history request
        if($_SERVER['REQUEST_METHOD'] == 'GET'){

            $result = $collection->find(['sid'=>$arguments[0]]);
            return $result->toArray();

        //Handle send message request
        }elseif($_SERVER['REQUEST_METHOD'] == 'POST'){

            $new_message = [
                'sid' => $arguments[0],
                'text' => file_get_contents('php://input'),
                'manager' => 0,
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