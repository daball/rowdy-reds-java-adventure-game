<?php

namespace engine;

require_once 'CommandProcessor.php';
require_once __DIR__.'/../playable/index.php';
require_once __DIR__.'/../game/RowdyRedMap.php';

use \game\RowdyRedMap;
use \map\Map;
use \playable\Player;
use \playable\System;

final class GameState implements \Serializable
{
  protected $map;
  protected $consoleHistory;
  protected $moves;
  protected $exiting;
  protected $knownAPIClasses;
  protected $locals;
  protected $globals;

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
    $this->map = RowdyRedMap::buildMap();
    $player = new Player();
    $player->location = $this->map->getSpawnPoint();
    $this->globals = array('me' => $player);
    $this->locals = array();
    $this->consoleHistory = "Game restarted." . $eol . $this->inspectRoom();
    $this->moves = 0;
    $this->exiting = false;
  }

  public function getPlayerRoom()
  {
    return $this->map->getRoom($this->getPlayer()->location);
  }

  public function getPlayer()
  {
    return $this->globals['me'];
  }

  public function addCommandToHistory($commandInput, $commandOutput)
  {
    $prompt = "> ";
    $eol = "\n";
    //in order to be functionally compatible with the prior app, we need to ignore empty inputs
    //rather than let the console drop an empty line, like consoles do
    if (trim($commandInput) != '' && $commandOutput != '') {
      if (strtolower($commandInput) == "restart" || strtolower($commandInput) == "reset")
        $this->consoleHistory .= $commandOutput;
      else
        $this->consoleHistory .= $eol . $prompt . $commandInput . $eol . $commandOutput;
    }
  }

  public function getConsoleHistory() {
    return $this->consoleHistory;
  }

  public function getMap() {
    return $this->map;
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

  protected static $instance = null;

  /**
   * Gets the GameState singleton object.
   */
  public static function getGameState($data = null)
  {
    if (!isset(static::$instance))
      static::init($data);
    else if (isset($data))
      static::$instance->unserialize($data);
    return static::$instance;
  }

  /**
   * Reinitializes the GameState.
   */
  public static function init($data = null) {
    if (isset($data))
      static::$instance = new GameState($data);
    else
      static::$instance = new GameState();
    return static::$instance;
  }

  protected function __construct()
  {
    //split off to the correct constructor
    $a = func_get_args();
    $i = func_num_args();
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a);
    }
  }

  protected function __construct0()
  {
    $eol = "\n";
    $this->resetGameState();
    $this->consoleHistory = "Game started." . $eol
            . $this->getPlayerRoom()->getComponent('Inspector')->inspect();
  }

  protected function __construct1($data)
  {
    $this->unserialize($data);
  }

  /* ISerializable interface implementation */

  public function serialize() {
    return serialize(
      array(
        'map' => $this->map,
        'consoleHistory' => $this->consoleHistory,
        'moves' => $this->moves,
        'exiting' => $this->exiting,
        'knownAPIClasses' => $this->knownAPIClasses,
        'locals' => $this->locals,
        'globals' => $this->globals,
      )
    );
  }

  public function unserialize($data) {
    //call static constructors, passing in the GameState
    $data = unserialize($data);
    $this->map = $data['map'];
    $this->consoleHistory = $data['consoleHistory'];
    $this->moves = $data['moves'];
    $this->exiting = $data['exiting'];
    $this->knownAPIClasses = $data['knownAPIClasses'];
    $this->locals = $data['locals'];
    $this->globals = $data['globals'];
  }
}
