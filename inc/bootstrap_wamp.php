<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.11.2021
 * Time: 12:31
 */

use FSChat\Wamp\MessageServer;

require('../vendor/autoload.php');
require('config.php');

$message_server = new MessageServer();

$loop   = React\EventLoop\Factory::create();
$zmq_context = new React\ZMQ\Context($loop);
$zmq_socket = $zmq_context->getSocket(ZMQ::SOCKET_PULL,CHAT_CHANNEL_PREFIX);
$zmq_socket->bind(ZMQ_BIND_URL);

$ws_socket = new React\Socket\Server(BROKER_BIND_URL, $loop);

$zmq_socket->on('message',[$message_server,'onMessage']);

$server = new \Ratchet\Server\IoServer(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new Ratchet\Wamp\WampServer($message_server)

        )
    ),
    $ws_socket
);

$loop->run();