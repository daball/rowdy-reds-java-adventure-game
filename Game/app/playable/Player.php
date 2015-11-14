<?php

namespace playable;

require_once 'IAssignable.php';
require_once __DIR__.'/../map/Direction.php';
require_once __DIR__.'/../engine/GameState.php';

use \map\Direction;
use engine\GameState;


/**
 * Player represents the player's avatar throughout the game.
 * @author David Ball
 */
class Player implements \Serializable
{
  /**
   * @ignore
   **/
  public static $gameState = null;

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
  public $location = null;

  /**
    * Navigates North.
    * @return String
    **/
  public function moveNorth() {
    return GameState::getGameState()->navigate(Direction::$north);
  }

  /**
    * Navigates South.
    * @return String
    **/
  public function moveSouth() {
    return GameState::getGameState()->navigate(Direction::$south);
  }

  /**
    * Navigates East.
    * @return String
    **/
  public function moveEast() {
    return GameState::getGameState()->navigate(Direction::$east);
  }

  /**
    * Navigates West.
    * @return String
    **/
  public function moveWest() {
    return GameState::getGameState()->navigate(Direction::$west);
  }

  /**
   * @ignore
   **/
  private function validateCollision(Room $room, $direction) {
    if ($direction->obstacleItem == null)
      return false;
    else
    {
      $item = GameState::getGameState()->getPlayerRoom()->getItem($direction->obstacleItem);
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
    $item = GameState::getGameState()->getPlayerRoom()->getItem($direction->obstacleItem);
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
    $gameState = GameState::getGameState();
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
      $nextDirection = GameState::getGameState()->getPlayerRoom()->getDirection($direction)->getComponent('Inspector')->inspect();
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

  /**
   * @ignore
   **/
  public function serialize() {
    return serialize(
      array(
        'location' => $this->location,
        'leftHand' => $this->leftHand,
        'rightHand' => $this->rightHand,
      )
    );
  }

  /**
   * @ignore
   **/
  public function unserialize($data) {
    $data = unserialize($data);
    $this->location = $data['location'];
    $this->leftHand = $data['leftHand'];
    $this->rightHand = $data['rightHand'];
  }
}
