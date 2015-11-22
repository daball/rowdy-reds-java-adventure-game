<?php

namespace game;

require_once 'Game.php';
require_once 'Direction.php';
require_once 'Room.php';
require_once __DIR__.'/../playable/index.php';
require_once __DIR__.'/../components/index.php';

use \Exception;
use \components\Assignable;
use \playable\BasicContainer;
use \playable\Key;
use \playable\Food;
use \playable\Door;
use \playable\LockedDoor;
use \playable\Dog;

function initialRoom($room) {
  $name = array_key_exists('name', $room) ? $room['name'] : 'A Room With No Name';
  $description = array_key_exists('description', $room) ? $room['description'] : 'A Room With No Description';
  $imageUrl = array_key_exists('description', $room) ? $room['imageUrl'] : 'null.png';
  $items = array_key_exists('items', $room) ? $room['items'] : array();

  return (new Room($name))->define(function ($room) use ($description, $imageUrl, $items) {
    $room->setImageUrl($imageUrl);
    $inspector = $room->getComponent("Inspector");
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($description) {
      return $description;
    });
    $container = $room->getComponent("Container");
    foreach ($items as $item) {
      switch ($item['type']) {
        case 'note':
          $container->insertItem((new GameObject($item['name']))->define(function ($note) use ($item) {
            $note->addComponent(new Assignable());
            $note->getComponent('Inspector')->onInspect(function ($inspector) use ($item) {
              return $item['description'];
            });
          }));
          break;
        case 'door':
          $container->insertItem(new Door($item['name'], $item['direction']));
          break;
        case 'lockedDoor':
          $container->insertItem(new LockedDoor($item['name'], $item['direction'], new Key($item['key.name'], 'rustySecret')));
          break;
        case 'key':
          $key = new Key($item['name'], $item['secret']);
          $key->define(function ($key) use ($item) {
            $inspector = $key->getComponent('Inspector');
            $inspector->onInspect(function ($inspector) use ($item) {
              return $item['description'];
            });
            if (array_key_exists('onAssign.room.imageUrl', $item)) {
              $initialOnAssign = $key->getComponent('Assignable')->onAssign();
              $key->getComponent('Assignable')->onAssign(function ($assignable, $oldTarget, $newTarget, $index) use ($initialOnAssign, $item) {
                $room = $oldTarget;
                $room->setImageUrl($item['onAssign.room.imageUrl']);
                return $initialOnAssign($assignable, $oldTarget, $newTarget, $index);
              });
            }
          });
          $container->insertItem($key);
          break;
        case 'food':
          $food = new Food($item['name']);
          $food->define(function ($food) use ($item) {
            $inspector = $food->getComponent('Inspector');
            $inspector->onInspect(function ($inspector) use ($item) {
              return $item['description'];
            });
          });
          $container->insertItem($food);
          break;
      }
    }
  });
}

/**
 *  The GameBuilder class helps automate building a Game by using
 *  the factory/builder design pattern. Each method will return
 *  the same GameBuilder instance so that you can continue calling
 *  methods on the object, allowing for simpler code to build a
 *  map. Call getGame() when you are finished.
 **/
class GameBuilder
{
  protected static $gameBuilders;
  protected $game = null;

  protected function __construct($gameName = null)
  {
    $this->game = new Game($gameName);
  }

  public static function newGame($gameName)
  {
    if (is_null(self::$gameBuilders))
      self::$gameBuilders = array();
    return self::$gameBuilders[$gameName] = new GameBuilder($gameName);
  }

  // public static function editGame($gameName)
  // {
  //   if (is_null(self::$gameBuilders))
  //     self::$gameBuilders = array();
  //   return self::$gameBuilders[$gameName] = new GameBuilder();
  // }

  public function insertRoom($room)
  {
    $this->game->addRoom($room);
    if (!$this->game->getSpawnPoint())
      $room->define(function ($room) {
        $room->setSpawnPoint();
      });
    return $this;
  }

  // public function setRoomDirectionDescription($roomName, $roomDirection, $roomDirectionDescription)
  // {
  //   $room = $this->game->getRoom($roomName);
  //   $roomDirection = $room->getDirection(Direction::cardinalDirection($roomDirection));
  //   $roomDirection->description = $roomDirectionDescription;
  //   return $this;
  // }

  public function connectRooms($roomName1, $room1Direction, $roomName2)
  {
    if (is_array($roomName1)) $roomName1 = $roomName1['name'];
    if (is_array($roomName2)) $roomName2 = $roomName2['name'];
    $room1 = $this->game->getRoom($roomName1);
    if ($room1 == null)
      throw new Exception("Room '$roomName1' not found");
    $room2 = $this->game->getRoom($roomName2);
    if ($room2 == null)
      throw new Exception("Room '$roomName2' not found");
    $room1Direction = Direction::cardinalDirection($room1Direction);
    $room2Direction = Direction::oppositeDirection($room1Direction);
    $room1->getDirection($room1Direction)->setNextRoom($room2);
    $room2->getDirection($room2Direction)->setNextRoom($room1);
    // if ($room1->getDirection($room1Direction)->obstacleItem !== null) {
    //   $itemName = $room1->getDirection($room1Direction)->obstacleItem;
    //   $item = $room1->items[$itemName];
    //   //copy item to $room2
    //   $room2->items[$itemName] = $item;
    //   //assign obstacle to $room2
    //   $room2->directions->getDirection($room2Direction)->obstacleItem = $itemName;
    // }
    // else if ($room2->getDirection($room2Direction)->obstacleItem !== null) {
    //   $itemName = $room2->getDirection($room2Direction)->obstacleItem;
    //   $item = $room2->items[$itemName];
    //   //copy item to $room1
    //   $room1->items[$itemName] = $item;
    //   //assign obstacle to $room1
    //   $room1->getDirection($room1Direction)->obstacleItem = $itemName;
    // }
    return $this;
  }

  public function setSpawnPoint($roomName)
  {
    if (is_array($roomName)) $roomName = $roomName['name'];
    foreach ($this->game->rooms as $r => $room)
    {
      if ($room->getName() === $roomName)
        $room->define(function ($room) {
          $room->setSpawnPoint();
        });
      else
      $room->define(function ($room) {
        $room->unsetSpawnPoint();
      });
    }
    return $this;
  }

  public function getGame()
  {
    return $this->game;
  }

  public static function getNamedGame($gameName)
  {
    if (isset(self::$gameBuilders) && array_key_exists($gameName, self::$gameBuilders))
      return self::$gameBuilders[$gameName]->getGame();
    return FALSE;
  }

  // public function insertObjectInRoom($roomName, $itemName, $item)
  // {
  //   $room = $this->game->getRoom($roomName);
  //   $room->setItem($itemName, $item);
  //   return $this;
  // }
  //
  // public function insertObstacleObjectInRoom($roomName, $roomDirection, $itemName, $item)
  // {
  //   $room = $this->game->getRoom($roomName);
  //   $room->getDirection($roomDirection)->obstacleItem = $itemName;
  //   $room->setItem($itemName, $item);
  //   if ($room->getDirection($roomDirection)->getNextRoom() !== "") {
  //     $room2 = $this->game->getRoom($room->getDirection($roomDirection)->getNextRoomName());
  //     //copy item to room2
  //     $room2->setItem($itemName, $item);
  //     $room2Direction = Direction::oppositeDirection($roomDirection);
  //     //assign collision object to other room
  //     $room2->getDirection($room2Direction)->obstacleItem = $itemName;
  //   }
  //   return $this;
  // }
}
