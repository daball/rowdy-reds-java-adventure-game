<?php

namespace engine;

require_once 'CommandProcessor.php';
require_once __DIR__.'/../game/GameBuilder.php';
require_once __DIR__.'/../game/Player.php';
require_once __DIR__.'/../playable/index.php';
require_once __DIR__.'/../util/PubSubMessageQueue.php';
require_once __DIR__.'/../util/BasicEnglish.php';
require_once __DIR__.'/../../vendor/autoload.php';

use \game\Game;
use \game\GameBuilder;
use \game\Player;
use \playable\System;
use \util\PubSubMessageQueue;
use \Opis\Closure\SerializableClosure;

final class GameState
{
  /**
   * The currently loaded game.
   **/
  protected $game;
  /**
   * Array of prior commands input into command window.
   **/
  protected $commandHistory;
  /**
   * Tablet code written by the player.
   **/
  protected $tabletCode;
  /**
   * Prior command I/O history (full text).
   **/
  protected $consoleHistory;
  protected $moves;
  protected $exiting;
  protected $knownAPIClasses;
  protected $locals;
  protected $globals;

  protected $messageQueues;

  public function getGame() {
    return $this->game;
  }

  public function setGame($game) {
    $eol = "\n";
    $this->game = $game;
    $this->resetGameState();
  }

  public function addCommandToHistory($commandInput, $commandOutput, $tabletCode)
  {
    $prompt = "> ";
    $eol = "\n";
    //in order to be functionally compatible with the prior app, we need to ignore empty inputs
    //rather than let the console drop an empty line, like consoles do
    if (trim($commandInput) != '' && $commandOutput != '') {
      array_push($this->commandHistory, $commandInput);
      if (strtolower($commandInput) == "restart" || strtolower($commandInput) == "reset")
        $this->consoleHistory .= $commandOutput;
      else
        $this->consoleHistory .= $eol . $prompt . $commandInput . $eol . $commandOutput;
    }
    $this->tabletCode = $tabletCode;
  }

  public function getConsoleHistory() {
    return $this->consoleHistory;
  }

  public function getTabletCode() {
    return $this->tabletCode;
  }

  public function getCommandHistory() {
    return $this->commandHistory;
  }

  public function getMoves() {
    return $this->moves;
  }

  public function incrementMoves() {
    return ++$this->moves;
  }

  public function isExiting($isExiting=null) {
    if ($isExiting !== null)
      $this->exiting = $isExiting;
    return $this->exiting;
  }

  public function isKnownAPIClass($className)
  {
    return in_array($className, $this->knownAPIClasses);
  }

  public function foundAPIClass($className)
  {
    array_push($this->knownAPIClasses, $className);
    return "You have unlocked the $className API. Read about $className using the javadoc notebook.";
  }

  public function inspectRoom() {
    $eol = "\n";
    $room = $this->getPlayerRoom();
    //inspect room
    $output = $room->getComponent('Inspector')->inspect();
    //print items in room
    $roomItems = $room->getComponent('Container')->getAllItems();
    $saRoomItems = array();
    foreach($roomItems as $value) { array_push($saRoomItems, insertAOrAn($value->getName())); }
    if (count($roomItems) > 0) {
      $sRoomItems = natural_language_join($saRoomItems);
      $output .= "  You see here $sRoomItems.";
    }
    //print obvious exits
    $obviousExits = array();
    if ($room->getDirection('u')->isNextRoomObvious()) array_push($obviousExits, "up");
    if ($room->getDirection('w')->isNextRoomObvious()) array_push($obviousExits, "west");
    if ($room->getDirection('n')->isNextRoomObvious()) array_push($obviousExits, "north");
    if ($room->getDirection('e')->isNextRoomObvious()) array_push($obviousExits, "east");
    if ($room->getDirection('s')->isNextRoomObvious()) array_push($obviousExits, "south");
    if ($room->getDirection('d')->isNextRoomObvious()) array_push($obviousExits, "down");
    $sObviousExits = natural_language_join($obviousExits);
    if (count($obviousExits) == 1)
      $output .= "  The obvious exit is $sObviousExits.";
    else if (count($obviousExits) > 1)
      $output .= "  The obvious exits are $sObviousExits.";
    return $output;
  }

  public function inspectRoomItem($itemName) {
    $roomItem = $itemName;
    $output = "";
    if (!is_a($itemName, '\game\GameObject')) {
      $room = $this->getPlayerRoom();
      $roomItem = $room->getComponent('Container')->findItemByName($itemName);
    }
    $output .= $roomItem->getComponent('Inspector')->inspect();
    if ($roomItem->hasComponent('Container')
      && (!$roomItem->hasComponent('Openable') || $roomItem->getComponent('Openable')->isOpened())
      && (!$roomItem->hasComponent('Lockable') || $roomItem->getComponent('Openable')->isUnlocked())) {
      $itemItems = $roomItem->getComponent('Container')->getAllItems();
      $saItemItems = array();
      foreach($itemItems as $value) { array_push($saItemItems, insertAOrAn($value->getName())); }
      if (count($itemItems) > 0) {
        $sItemItems = natural_language_join($saItemItems);
        $output .= "  You see inside $sItemItems.";
      }
    }
    return $output;
  }

  public function resetGameState()
  {
    $eol = "\n";
    $this->messageQueues = null;
    $this->knownAPIClasses = array(
      //System classes
      'System', 'PrintStream', 'OutputStream',
      //Player classes
      'Player', 'Assignable', 'Door', 'Openable'
    );
    $player = new Player();
    $this->game = GameEngine::loadGame($this->getGame()->getName());
    $player->setLocation($this->getGame()->getSpawnPoint());
    $this->globals = array('me' => $player);
    $this->locals = array();
    $this->commandHistory = array();
    $this->consoleHistory = $this->inspectRoom();
    $this->tabletCode = "";
    $this->moves = 0;
    $this->exiting = false;
  }

  public function getPlayerRoom()
  {
    return $this->getGame()->getRoom($this->getPlayer()->getLocation());
  }

  public function getPlayer()
  {
    return $this->globals['me'];
  }

  public function persistentSubscribe($queue, $subscriber) {
    if (!isset($this->messageQueues))
      $this->messageQueues = array();
    if (!array_key_exists($queue, $this->messageQueues))
      $this->messageQueues[$queue] = array (
        'subscribers' => array(),
      );
    $subscriber = new SerializableClosure($subscriber);
    array_push($this->messageQueues[$queue]['subscribers'], $subscriber);
    PubSubMessageQueue::subscribe($queue, $subscriber);
  }

  protected function resubscribeAll() {
    if ($this->messageQueues && count($this->messageQueues))
      foreach($this->messageQueues as $queueName=>$queue) {
        foreach($queue['subscribers'] as $subscriber) {
          PubSubMessageQueue::subscribe($queueName, $subscriber);
        }
      }
  }

  protected static $instance = null;

  /**
   * Gets the GameState singleton object.
   */
  public static function getInstance($data = null)
  {
    if (isset($data)) {
      if (GameEngine::isValidGame($data)) {
        static::init($data);
      }
      else {
        static::$instance = unserialize($data);
        //wake up embedded subscriptions
        static::$instance->resubscribeAll();
        //notify late-binding persistent subscribers to begin working
        PubSubMessageQueue::publish(static::$instance, "GameState", "ready");
      }
    }
    return static::$instance;
  }

  /**
   * (Re)-/Initializes the GameState.
   */
  public static function init($game) {
    if (GameEngine::isValidGame($game))
      static::$instance = new GameState($game);
    //notify late-binding persistent subscribers to begin working
    PubSubMessageQueue::publish(static::$instance, "GameState", "ready");
    return static::$instance;
  }

  protected function __construct($game)
  {
    $this->setGame(GameEngine::loadGame($game));
  }
}
