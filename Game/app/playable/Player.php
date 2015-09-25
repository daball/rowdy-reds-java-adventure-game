<?php

namespace playable;

use \map\Direction;

require_once 'IAssignable.php';
require_once __DIR__.'/../map/Direction.php';

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

  public static function init($gameState) {
    self::$gameState =  $gameState;
  }

  /**
    * Navigates North.
    * @return String
    **/
  public function navigateNorth() {
    return self::$gameState->navigate(Direction::$north);
  }

  /**
    * Navigates South.
    * @return String
    **/
  public function navigateSouth() {
    return self::$gameState->navigate(Direction::$south);
  }

  /**
    * Navigates East.
    * @return String
    **/
  public function navigateEast() {
    return self::$gameState->navigate(Direction::$east);
  }

  /**
    * Navigates West.
    * @return String
    **/
  public function navigateWest() {
    return self::$gameState->navigate(Direction::$west);
  }

  private function validateCollision($direction) {
    if ($direction->obstacleItem == null)
      return false;
    else
    {
      $item = self::$gameState->getPlayerRoom()->items[$direction->obstacleItem];
      if (is_a($item, "\playable\ICollidable"))
        return $item->isInTheWay();
      else
        return false;
    }
  }

  private function explainCollision($d, $direction) {
    $item = self::$gameState->getPlayerRoom()->items[$direction->obstacleItem];
    $d = ($d == Direction::$n ? 'north' : '') .
         ($d == Direction::$s ? 'south' : '') .
         ($d == Direction::$e ? 'east' : '') .
         ($d == Direction::$w ? 'west' : '') . '';
    if (is_a($item, "\playable\Door"))
      return "There is a door blocking you from going $d.";
    else
      return "There is an ICollidable object in the way, but I don't know what it is. Ask your friendly developer to update Player->explainCollision() so that you can play the game. In fact, this is a good time for a bug report.";
  }

  /**
    * Navigates West.
    * @return String
    **/
  public function navigate($direction)
  {
    //sanitize direction
    $direction = Direction::cardinalDirection($direction);
    //get adjacent room
    $directionInfo = self::$gameState->getPlayerRoom()->directions->getDirection($direction);
    $nextRoom = $directionInfo->nextRoom;
    //make sure this is valid
    if ($nextRoom !== '') {
      if ($this->validateCollision($directionInfo))
      {
        return $this->explainCollision($direction, $directionInfo);
      }
      else {
        //put the avatar in the next room
        $this->location = self::$gameState->map->getRoom($nextRoom)->name;
        //return next room description
        return self::$gameState->getPlayerRoom()->inspect();
      }
    }
    else {
      //room didn't exist, check if direction has a description
      $nextDirection = self::$gameState->getPlayerRoom()->directions->getDirection($direction)->description;
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
