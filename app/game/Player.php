<?php

namespace game;

require_once 'GameObject.php';
require_once __DIR__.'/../game/Direction.php';
require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../components/index.php';
require_once __DIR__.'/../util/PubSubMessageQueue.php';

use \engine\GameState;
use \game\Direction;
use \components\Container;
use \components\Inspector;
use \playable\BasicContainer;
use \util\PubSubMessageQueue;

/**
 * Player represents the player's avatar throughout the game.
 * @author David Ball
 */
class Player extends GameObject
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

  /**
   * Player's equipment equipped in the game.
   * @var String
   * @ignore
   **/
  protected $equipment = null;

  public function __construct() {
    parent::__construct("Rowdy Red");
    $handDefinition = function ($which) {
      return function ($hand) use ($which) {
        $hand->addComponent(new Container())->define(function ($container) {
          $container->setMaxItems(1);
        });
        $hand->addComponent(new Inspector())->define(function ($inspector) use ($which) {
          $inspector->onInspect(function ($inspector) use ($which) {
            $hand = $inspector->getParent();
            $container = $hand->getComponent('Container');
            if (!$container->countItems() && !$container->getItemAt(0))
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
    $room = $gameState->getPlayerRoom();
    //sanitize direction
    $direction = Direction::cardinalDirection($direction);
    //get adjacent room
    $directionInfo = $room->getDirection($direction);
    //get next room
    $nextRoom = $gameState->getGame()->getRoom($directionInfo->getNextRoomName());
    //make sure this is valid
    if ($nextRoom) {
      //is the room dark? if so you can only go back where you came from
      if ($room->isDark() && $room->getLastRoomName() != $nextRoom->getName()) {
        return "The room is too dark to see where you are going.";
      }
      if ($this->validateCollision($room, $direction))
      {
        return $this->explainCollision($room, $direction);
      }
      else {
        //put the avatar in the next room
        $this->setLocation($nextRoom->getName());
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
      return "You cannot go " . Direction::fullDirection($direction) . '.';
    }
  }

  public function getLocation() {
    return $this->location;
  }

  public function setLocation($location) {
    $oldLocation = $this->getLocation();
    $this->location = $location;
    PubSubMessageQueue::publish($this, "Player", array(
      'action' => 'setLocation',
      'oldLocation' => $oldLocation,
      'newLocation' => $this->getLocation(),
    ));
  }

  public function getLeftHand() {
    return $this->leftHand;
  }

  public function getRightHand() {
    return $this->rightHand;
  }

  public function getBackpack() {
    return $this->getEquipmentItem("backpack");
  }

  public function getEquipment() {
    return $this->equipment;
  }

  public function equipItem($item) {
    if ($item->hasComponent('Equippable')) {
      if (!isset($this->equipment))
        $this->equipment = new BasicContainer('playerEquipment');
      $output = $item->getComponent('Equippable')->equip();
      $itemCurrentContainer = $item->getContainer();
      if ($itemCurrentContainer != null)
        $itemCurrentContainer->getComponent('Container')->removeItem($item);
      $this->equipment->getComponent('Container')->insertItem($item);
      return $output;
    }
    return "";
  }

  public function listEquipment() {
    $output = array();
    if (isset($this->equipment)) {
      $items = $this->equipment->getComponent('Container')->getAllItems();
      foreach ($items as $item)
        array_push($output, $item->getName());
    }
    return $output;
  }

  public function hasEquipmentItem($itemName) {
    return in_array($itemName, $this->listEquipment());
  }

  public function getEquipmentItem($itemName) {
    if (isset($this->equipment))
      return $this->equipment->getComponent('Container')->findItemByName($itemName);
    return null;
  }
}
