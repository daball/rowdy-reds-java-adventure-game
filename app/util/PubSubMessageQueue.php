<?php

namespace util;

/**
 * A simple publish/subscribe messaging queue.
 *
 * How To Use:
 *  - Create a message queue.
 *  - Subscribe to a message queue.
 *  - Publish a message to the queue.
 *  - Subscribers get the published messages.
 *
 * Implementation notes:
 *  - This implementation does not require subscribers to enroll prior to
 *      publishing messages.
 *  - Late subscribers can still read all the messages published to the queue,
 *      even those published earlier.
 *  - Message queues only last the lifetime of the request. They are only
 *      intended to be used throughout a request lifecycle and do not get
 *      stored into the session.
 *
 * @author David Ball <daball@email.radford.edu>
 **/
class PubSubMessageQueue {

  /**
   * Message queues associative array
   * Queue name is the item index
   * Each item contains another associative array with:
   *   messages: 0-based index array with start to finish messages sent
   *     - each message in the array has the format:
   *         sender: the object which sent the message
   *         message: the message itself, which can be any format the sender
   *                    prefers
   *   subscribers: 0-based index array of subscriber callback closures
   *     - subscriber callback should be in the format:
   *         function ($sender, $queue, $message) { }
   **/
  private static $messageQueues;

  public static function init()
  {
    self::$messageQueues = array();
    self::registerQueue('default');
  }

  public static function registerQueue($queue)
  {
    if (!array_key_exists($queue, self::$messageQueues))
      self::$messageQueues[$queue] = array (
        'messages' => array(),
        'subscribers' => array(),
      );
  }

  public static function publish($sender, $queue, $message)
  {
    self::registerQueue($queue);
    array_push(self::$messageQueues[$queue]['messages'], array(
      'sender' => $sender,
      'message' => $message
    ));
    //post message to all subscribers
    foreach (self::$messageQueues[$queue]['subscribers'] as $subscriber) {
      $subscriber($sender, $queue, $message);
    }
    if ($queue != "Logger" && $sender != "self") {
      self::publish("self", "Logger", "PubSubMessageQueue::publish() - Queue $queue - Message " . var_export($message, true));
    }
  }

  public static function subscribe($queue, $subscriber)
  {
    self::registerQueue($queue);
    array_push(self::$messageQueues[$queue]['subscribers'], $subscriber);
    //post all prior messages to new subscriber
    foreach (self::$messageQueues[$queue]['messages'] as $message) {
      $subscriber($message['sender'], $queue, $message['message']);
    }
    self::publish("self", "Logger", "PubSubMessageQueue::subscribe() - Queue $queue subscription registered.");
  }

}

PubSubMessageQueue::init();
