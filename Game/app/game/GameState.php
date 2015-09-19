<?php

require_once 'SampleMap.php';
require_once __DIR__.'/../player/Player.php';
require_once __DIR__.'/../player/System.php';

class GameState
{
  public $map;
  public $consoleHistory;
  public $moves;
  public $isExiting;
  public $knownAPIClasses;
  public $locals;
  public $globals;

  public function isKnownAPIClass($className)
  {
    return in_array($className, $this->knownAPIClasses);
  }

  public function foundAPIClass($className)
  {
    array_push($this->knownAPIClasses, $className);
    return "You have unlocked the $className API. Read about $className using the javadoc notebook.";
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
    $this->map = SampleMap::buildSampleMap();
    $player = new Player();
    $player->location = $this->map->getSpawnPoint();
    $this->globals = array('player' => $player);
    $this->locals = array();
    $this->consoleHistory = "Game restarted." . $eol . $this->inspectRoom();;
    $this->moves = 0;
    $this->isExiting = false;
  }

  public function getAvatarRoom()
  {
    return $this->map->getRoom($this->getPlayer()->location);
  }

  public function navigate($direction)
  {
    //sanitize direction
    $direction = Direction::getDirection($direction);
    //get adjacent room
    $nextRoom = $this->getAvatarRoom()->directions[$direction]->jumpTo;
    //make sure this is valid
    if ($nextRoom !== '') {
      //put the avatar in the next room
      $this->globals['player']->location = $this->map->getRoom($nextRoom)->name;
      //return next room description
      return $this->inspectRoom();
    }
    else {
      //room didn't exist, check if direction has a description
      $nextDirection = $this->getAvatarRoom()->directions[$direction]->description;
      if ($nextDirection !== '')
        //return description of the direction
        return $nextDirection;
      //direction did not have a description, return generic error
      return "You cannot go " .
        ($direction == Direction::$n ? 'north' : '') .
        ($direction == Direction::$s ? 'south' : '') .
        ($direction == Direction::$e ? 'east' : '') .
        ($direction == Direction::$w ? 'west' : '') . '.';
    }
  }

  public function inspectRoom()
  {
    return $this->getAvatarRoom()->description;
  }

  public function getPlayer()
  {
    return $this->globals['player'];
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

  function __construct()
  {
    //call static constructors, passing in the GameState
    Player::init($this);
    System::init($this);
    //split off to the correct constructor
    $a = func_get_args();
    $i = func_num_args();
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a);
    }
  }
  public function __construct0()
  {
    $eol = "\n";
    $this->resetGameState();
    $this->consoleHistory = "Game started." . $eol . $this->inspectRoom();
  }
  public function __construct1($data)
  {
    $this->unserialize($data);
  }

  public function serialize() {
    return serialize(
      array(
        'map' => $this->map,
        'consoleHistory' => $this->consoleHistory,
        'moves' => $this->moves,
        'isExiting' => $this->isExiting,
        'knownAPIClasses' => $this->knownAPIClasses,
        'locals' => $this->locals,
        'globals' => $this->globals,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->map = $data['map'];
    $this->consoleHistory = $data['consoleHistory'];
    $this->moves = $data['moves'];
    $this->isExiting = $data['isExiting'];
    $this->knownAPIClasses = $data['knownAPIClasses'];
    $this->locals = $data['locals'];
    $this->globals = $data['globals'];
  }
}
