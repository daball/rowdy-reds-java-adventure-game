<?php

namespace engine;

require_once 'CommandProcessor.php';
require_once __DIR__.'/../game/GameBuilder.php';
require_once __DIR__.'/../game/Player.php';
require_once __DIR__.'/../playable/index.php';
require_once __DIR__.'/../util/BasicEnglish.php';

use \game\Game;
use \game\GameBuilder;
use \game\Player;
use \playable\System;

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
   * Prior command I/O history (full text).
   **/
  protected $consoleHistory;
  protected $moves;
  protected $exiting;
  protected $knownAPIClasses;
  protected $locals;
  protected $globals;

  public function getGame() {
    return $this->game;
  }

  public function setGame($game) {
    $eol = "\n";
    $this->game = $game;
    $this->resetGameState();
  }

  public function addCommandToHistory($commandInput, $commandOutput)
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
  }

  public function getConsoleHistory() {
    return $this->consoleHistory;
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

  public function inspectItemInRoom($itemName) {
    $room = $this->getPlayerRoom();
    //inspect room
    $roomItem = $room->getComponent('Container')->findItemByName($itemName);
    // if ($roomItem)
  }

  public function resetGameState()
  {
    $eol = "\n";
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
    return static::$instance;
  }

  protected function __construct($game)
  {
    $eol = "\n";
    $this->setGame(GameEngine::loadGame($game));
  }
}