<?php

require_once 'SampleMap.php';

class GameState
{
  public $map;
  public $avatarLocation;
  public $consoleHistory;

  public function resetGameState()
  {
    $this->map = SampleMap::buildSampleMap();
    $this->avatarLocation = $this->map->getSpawnPoint();
    $this->consoleHistory = "Game restarted.";
  }

  public function getAvatarRoom()
  {
    return $this->map->getRoom($this->avatarLocation);
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
      $this->avatarLocation = $this->map->getRoom($nextRoom)->name;
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

  public function __construct()
  {
    $this->resetGameState();
    $this->consoleHistory = "Game started.";
  }
}
