<?php

namespace util;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../util/PubSubMessageQueue.php';

use \engine\GameState;
use \util\PubSubMessageQueue;

trait TMessenger {
  public function subscribe($queue, $subscriber) {
    $gameState = GameState::getInstance();
    if ($gameState)
      //normal binding after GameState is available
      return $gameState->persistentSubscribe($queue, $subscriber);
    else {
      //late binding to GameState, ie, wait for GameState to become available
      $oneAndDone = false;
      PubSubMessageQueue::subscribe("GameState", function ($sender, $queueName, $message) use (&$oneAndDone, $queue, $subscriber) {
        if ($message == "ready") {
          $gameState = GameState::getInstance();
          $gameState->persistentSubscribe($queue, $subscriber);
        }
      });
    }
  }
  public function publish($queue, $message) {
    return PubSubMessageQueue::publish($this, $queue, $message);
  }
}
