<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.11.2021
 * Time: 18:33
 */

namespace FSChat\Wamp;

class MessageServer implements \Ratchet\Wamp\WampServerInterface
{

    protected $clients;

    protected $subscribedTopics = array();

    /**
     * BidServer constructor.
     * @param $clients
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(\Ratchet\ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(\Ratchet\ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  \Ratchet\ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(\Ratchet\ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * An RPC call has been received
     * @param \Ratchet\ConnectionInterface $conn
     * @param string $id The unique ID of the RPC, required to respond to
     * @param string|\Ratchet\Wamp\Topic $topic The topic to execute the call against
     * @param array $params Call parameters received from the client
     */
    function onCall(\Ratchet\ConnectionInterface $conn, $id, $topic, array $params)
    {
        $conn->close();
    }

    /**
     * A request to subscribe to a topic has been made
     * @param \Ratchet\ConnectionInterface $conn
     * @param string|\Ratchet\Wamp\Topic $topic The topic to subscribe to
     */
    function onSubscribe(\Ratchet\ConnectionInterface $conn, $topic)
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * A request to unsubscribe from a topic has been made
     * @param \Ratchet\ConnectionInterface $conn
     * @param string|\Ratchet\Wamp\Topic $topic The topic to unsubscribe from
     */
    function onUnSubscribe(\Ratchet\ConnectionInterface $conn, $topic)
    {
        //do nothing
    }

    /**
     * A client is attempting to publish content to a subscribed connections on a URI
     * @param \Ratchet\ConnectionInterface $conn
     * @param string|\Ratchet\Wamp\Topic $topic The topic the user has attempted to publish to
     * @param string $event Payload of the publish
     * @param array $exclude A list of session IDs the message should be excluded from (blacklist)
     * @param array $eligible A list of session Ids the message should be send to (whitelist)
     */
    function onPublish(\Ratchet\ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $conn->close();
    }

    public function onMessage($data_string){

        $data = json_decode($data_string);

        $topic_id = CHAT_CHANNEL_PREFIX.'_'.$data->sid;

        if(!isset($this->subscribedTopics[$topic_id])) return;

        $topic = $this->subscribedTopics[$topic_id];

        $topic->broadcast($data);
    }
}