<?php

namespace game;

require_once __DIR__.'/../game/Direction.php';
require_once __DIR__.'/../engine/GameState.php';
require_once 'GameObject.php';
require_once __DIR__.'/../components/index.php';

use \game\Direction;
use \engine\GameState;
use \components\Container;
use \components\Inspector;

/**
 * Player represents the player's avatar throughout the game.
 * @author David Ball
 */
class Player
{
  /**
   * Player's left hand.
   * @var GameObject
   **/
  public $leftHand = null;

  /**
   * Player's right hand.
   * @var GameObject
   **/
  public $rightHand = null;

  /**
   * Player's location in the game.
   * @var String
   * @ignore
   **/
  protected $location = null;

  public function __construct() {
    $handDefinition = function ($which) {
      return function ($hand) use ($which) {
        $hand->addComponent(new Container())->define(function ($container) {
          $container->setMaxItems(1);
        });
        $hand->addComponent(new Inspector())->define(function ($inspector) use ($which) {
          $inspector->onInspect(function ($inspector) use ($which) {
            $hand = $inspector->getParent();
            $container = $hand->getComponent('Container');
            if (!$container->countItems())
              return "Your $which hand is empty.";
            else {
              $item = $container->getItemAt(0);
              $itemInspector = $item->getComponent('Inspector');
              if (!$itemInspector)
                return "The item in your $which hand is not inspectable.";
              else
                return $itemInspector->inspect();
            }
          });
        });
      };
    };

    $this->leftHand = (new GameObject('leftHand'))->define($handDefinition('left'));
    $this->rightHand = (new GameObject('rightHand'))->define($handDefinition('right'));
  }

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
    $colliderItem = $room->getColliderItemInDirection($direction);
    return $colliderItem && $colliderItem->getComponent('Collider')->isEnabled();
  }

  /**
   * @ignore
   **/
  private function explainCollision(Room $room, $direction) {
    $colliderItem = $room->getColliderItemInDirection($direction);
    return $colliderItem->getComponent('Collider')->collide($direction);
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
    $nextRoom = $gameState->getGame()->getRoom($directionInfo->getNextRoomName());
    //make sure this is valid
    if ($nextRoom) {
      if ($this->validateCollision($gameState->getPlayerRoom(), $direction))
      {
        return $this->explainCollision($gameState->getPlayerRoom(), $direction);
      }
      else {
        //put the avatar in the next room
        $this->location = $nextRoom->getName();
        //return next room description
        return $gameState->inspectRoom();
      }
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

  public function getLeftHand() {
    return $this->leftHand;
  }

  public function getRightHand() {
    return $this->rightHand;
  }
}