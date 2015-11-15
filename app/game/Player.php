<?php

namespace game;

require_once __DIR__.'/../game/Direction.php';
require_once __DIR__.'/../engine/GameState.php';

use \game\Direction;
use \engine\GameState;

/**
 * Player represents the player's avatar throughout the game.
 * @author David Ball
 */
class Player
{
  /**
   * Player's left hand.
   * @var IAssignable
   **/
  public $leftHand = null;

  /**
   * Player's right hand.
   * @var IAssignable
   **/
  public $rightHand = null;

  /**
   * Player's location in the game.
   * @var IAssignable
   * @ignore
   **/
  protected $location = null;

  /**
    * Navigates North.
    * @return String
    **/
  public function moveNorth() {
    return GameState::getInstance()->navigate(Direction::$north);
  }

  /**
    * Navigates South.
    * @return String
    **/
  public function moveSouth() {
    return GameState::getInstance()->navigate(Direction::$south);
  }

  /**
    * Navigates East.
    * @return String
    **/
  public function moveEast() {
    return GameState::getInstance()->navigate(Direction::$east);
  }

  /**
    * Navigates West.
    * @return String
    **/
  public function moveWest() {
    return GameState::getInstance()->navigate(Direction::$west);
  }

  /**
   * @ignore
   **/
  private function validateCollision(Room $room, $direction) {
    if ($direction->obstacleItem == null)
      return false;
    else
    {
      $item = GameState::getInstance()->getPlayerRoom()->getItem($direction->obstacleItem);
      if (is_a($item, "\playable\ICollidable"))
        return $item->isInTheWay();
      else
        return false;
    }
  }

  /**
   * @ignore
   **/
  private function explainCollision(Room $room, $direction) {
    $item = GameState::getInstance()->getPlayerRoom()->getItem($direction->obstacleItem);
    $d = ($d == Direction::$n ? 'north' : '') .
         ($d == Direction::$s ? 'south' : '') .
         ($d == Direction::$e ? 'east' : '') .
         ($d == Direction::$w ? 'west' : '') . '';
    return $item->explainCollision($d);
  }

  /**
    * Navigates in the given direction.
    * @return String
    * @ignore
    **/
  public function navigate($direction)
  {
    $gameState = GameState::getInstance();
    //sanitize direction
    $direction = Direction::cardinalDirection($direction);
    //get adjacent room
    $directionInfo = $gameState->getPlayerRoom()->getDirection($direction);
    $nextRoom = $directionInfo->getNextRoomName();
    //make sure this is valid
    if ($nextRoom !== '') {
      // if ($this->validateCollision($nextRoom, $direction))
      // {
      //   return $this->explainCollision($nextRoom, $direction);
      // }
      // else {
        //put the avatar in the next room
        $this->location = $nextRoom;
        //return next room description
        return $gameState->getPlayerRoom()->getComponent('Inspector')->inspect();
      // }
    }
    else {
      //room didn't exist, check if direction has a description
      $nextDirection = GameState::getInstance()->getPlayerRoom()->getDirection($direction)->getComponent('Inspector')->inspect();
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

  public function getLocation() {
    return $this->location;
  }

  public function setLocation($location) {
    $this->location = $location;
  }
}
