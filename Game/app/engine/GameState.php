<?php

namespace engine;

require_once 'CommandProcessor.php';
require_once __DIR__.'/../game/GameBuilder.php';
require_once __DIR__.'/../game/Player.php';
require_once __DIR__.'/../playable/index.php';

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
    $this->consoleHistory = "Game started." . $eol
            . $this->getPlayerRoom()->getComponent('Inspector')->inspect();
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

  public function isExiting() {
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
    $output = $this->getPlayerRoom()->getComponent('Inspector')->inspect() . $eol;
    $roomItems = $this->getPlayerRoom()->getComponent('Container')->getAllItems();
    if (count($roomItems) > 0)
      $output .= "The following items are available in this area:$eol";
    foreach($roomItems as $item => $value)
    {
      //$output .= \java\JavaReflection::inspectInstance($value, $item) . $eol;
      $class = new ReflectionClass($value);
      $className = $class->getShortName();
      $output .= "$className $item;";
    }
    return $output;
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
    $this->consoleHistory = "Game restarted." . $eol . $this->inspectRoom();
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
