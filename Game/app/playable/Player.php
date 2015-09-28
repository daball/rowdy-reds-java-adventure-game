<?php

namespace playable;

use \map\Direction;
use game\GameState;

require_once 'IAssignable.php';
require_once __DIR__.'/../map/Direction.php';
require_once __DIR__.'/../game/GameState.php';

/**
 * Player represents the player's avatar throughout the game.
 * @author David Ball
 */
class Player
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
  private function validateCollision($direction) {
    if ($direction->obstacleItem == null)
      return false;
    else
    {
      $item = GameState::getGameState()->getPlayerRoom()->items[$direction->obstacleItem];
      if (is_a($item, "\playable\ICollidable"))
        return $item->isInTheWay();
      else
        return false;
    }
  }

  /**
   * @ignore
   **/
  private function explainCollision($d, Direction $direction) {
    $item = GameState::getGameState()->getPlayerRoom()->items[$direction->obstacleItem];
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
    //sanitize direction
    $direction = Direction::cardinalDirection($direction);
    //get adjacent room
    $directionInfo = GameState::getGameState()->getPlayerRoom()->directions->getDirection($direction);
    $nextRoom = $directionInfo->nextRoom;
    //make sure this is valid
    if ($nextRoom !== '') {
      if ($this->validateCollision($directionInfo))
      {
        return $this->explainCollision($direction, $directionInfo);
      }
      else {
        //put the avatar in the next room
        $this->location = GameState::getGameState()->map->getRoom($nextRoom)->name;
        //return next room description
        return GameState::getGameState()->getPlayerRoom()->inspect();
      }
    }
    else {
      //room didn't exist, check if direction has a description
      $nextDirection = GameState::getGameState()->getPlayerRoom()->directions->getDirection($direction)->description;
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
