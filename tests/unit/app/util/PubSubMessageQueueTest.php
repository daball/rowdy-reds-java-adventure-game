<?php

namespace util\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/util/PubSubMessageQueue.php';

use \util\PubSubMessageQueue;

class PubSubMessageQueueTest extends \PHPUnit_Framework_TestCase
{
  public function testClosureStyle() {
    //prove the test type works
    $a = array();
    //pass $a by ref to a closure, write to $a, ensure $a has contents outside closure
    $c = function () use (&$a) {
      $a['b'] = '123';
    };
    $c();
    $this->assertTrue(array_key_exists('b', $a));
    $this->assertEquals('123', $a['b']);
  }

  public function testDogFirstBowlLater() {
    //For this test, we will subscribe first, publish second

    //reinit the pubsub, to make sure its empty already
    PubSubMessageQueue::init();
    //lets register a queue for the dog puzzle
    //I'm using a URI pattern, but it doesn't matter much what you call it.
    $queue = 'game://Iteration 1/Taxidermy Room/Dog Puzzle';

    //start off by subscribing to the queue
    $messagesDogHeard = array();
    $dogSubscriber = PubSubMessageQueue::subscribe($queue, function ($sender, $queue, $message) use (&$messagesDogHeard) {
      array_push($messagesDogHeard, array('sender'=>$sender, 'queue'=>$queue, 'message'=>$message));
    });

    //publish a message to any subscribers
    $sender = 'game://Iteration 1/Taxidermy Room/bowl';
    PubSubMessageQueue::publish($sender, $queue, array('action'=>'assign', 'item'=>'lambChop'));

    //now check to see if the dog got the message
    $this->assertEquals(1, count($messagesDogHeard));
    $this->assertEquals($queue, $messagesDogHeard[0]['queue']);
    $this->assertEquals($sender, $messagesDogHeard[0]['sender']);
    $this->assertEquals('assign', $messagesDogHeard[0]['message']['action']);
    $this->assertEquals('lambChop', $messagesDogHeard[0]['message']['item']);
  }

  public function testBowlFirstDogLater() {
    //For this test, we will publish first, subscribe second

    //reinit the pubsub, to make sure its empty already
    PubSubMessageQueue::init();
    //lets register a queue for the dog puzzle
    //I'm using a URI pattern, but it doesn't matter much what you call it.
    $queue = 'game://Iteration 1/Taxidermy Room/Dog Puzzle';

    //start off by publishing a message to any subscribers (none yet)
    $sender = 'game://Iteration 1/Taxidermy Room/bowl';
    PubSubMessageQueue::publish($sender, $queue, array('action'=>'assign', 'item'=>'lambChop'));

    //then subscribe to the queue, should get the messages anyways
    $messagesDogHeard = array();
    $dogSubscriber = PubSubMessageQueue::subscribe($queue, function ($sender, $queue, $message) use (&$messagesDogHeard) {
      array_push($messagesDogHeard, array('sender'=>$sender, 'queue'=>$queue, 'message'=>$message));
    });

    //now check to see if the dog got the message
    $this->assertEquals(1, count($messagesDogHeard));
    $this->assertEquals($queue, $messagesDogHeard[0]['queue']);
    $this->assertEquals($sender, $messagesDogHeard[0]['sender']);
    $this->assertEquals('assign', $messagesDogHeard[0]['message']['action']);
    $this->assertEquals('lambChop', $messagesDogHeard[0]['message']['item']);
  }
}
