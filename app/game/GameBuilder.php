<?php

namespace game;

require_once 'Game.php';
require_once 'Direction.php';
require_once 'Room.php';
require_once __DIR__.'/../components/index.php';
require_once __DIR__.'/../playable/index.php';

use \Exception;
use \components\Assignable;
use \playable\BasicContainer;
use \playable\Key;
use \playable\Food;
use \playable\Door;
use \playable\Equipment;
use \playable\LockedDoor;
use \playable\Dog;
use \playable\Lamp;
use \playable\GeneralObject;

/**
 * Constructs a Door object based on the item definition.
 *
 * @param $room Room definition (associative array).
 **/
function assembleRoom($room) {
  $name = array_key_exists('name', $room) ? $room['name'] : 'A Room With No Name';
  $meta = $room;

  return (new Room($name))->define(function ($room) use ($meta) {
    $description = array_key_exists('description', $meta) ? $meta['description'] : 'A Room With No Description';
    $imageUrl = array_key_exists('description', $meta) ? $meta['imageUrl'] : 'null.png';
    $items = array_key_exists('items', $meta) ? $meta['items'] : array();
    $dark = array_key_exists('dark', $meta) ? $meta['dark'] : false;
    $lampOnWindImageUrl = array_key_exists('lamp.wind.imageUrl', $meta) ? $meta['lamp.wind.imageUrl'] : "";
    $lampOnUnwindImageUrl = array_key_exists('lamp.unwind.imageUrl', $meta) ? $meta['lamp.unwind.imageUrl'] : "";

    $room->setImageUrl($imageUrl);
    $inspector = $room->getComponent("Inspector");
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($description) {
      return $description;
    });
    $container = $room->getComponent("Container");
    $container = assembleItemsIntoContainer($container, $items);
    if ($lampOnWindImageUrl)
      $room->subscribe('Lamp', function ($sender, $queue, $message) use ($room, $lampOnWindImageUrl) {
        if ($message == "wind")
          $room->setImageUrl($lampOnWindImageUrl);
      });
    if ($lampOnUnwindImageUrl)
      $room->subscribe('Lamp', function ($sender, $queue, $message) use ($room, $lampOnUnwindImageUrl) {
        if ($message == "unwind")
          $room->setImageUrl($lampOnUnwindImageUrl);
      });
  });
}

function assembleItemsIntoContainer($container, $items) {
  foreach ($items as $item) {
    switch ($item['type']) {
      case 'note':
        $container->insertItem(assembleNote($container, $item));
        break;
      case 'door':
        $container->insertItem(assembleDoor($container, $item));
        break;
      case 'lockedDoor':
        $container->insertItem(assembleLockedDoor($container, $item));
        break;
      case 'key':
        $container->insertItem(assembleKey($container, $item));
        break;
      case 'food':
        $container->insertItem(assembleFood($container, $item));
        break;
      case 'lamp':
        $container->insertItem(assembleLamp($container, $item));
        break;
      case 'equipment':
        $container->insertItem(assembleEquipment($container, $item));
        break;
      case 'generalObject':
        $container->insertItem(assembleGeneralObject($container, $item));
    }
  }
  return $container;
}

/**
 * Constructs a Note object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleNote($room, $item) {
  return ((new GameObject($item['name']))->define(function ($note) use ($item) {
    $note->addComponent(new Assignable());
    $note->getComponent('Inspector')->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
  }));
}

/**
 * Constructs a Door object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleDoor($room, $item) {
  return (new Door($item['name'], $item['direction']));
}

/**
 * Constructs a LockedDoor object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleLockedDoor($room, $item) {
  return (new LockedDoor($item['name'], $item['direction'], $item['secret']));
}

/**
 * Constructs a Key object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleKey($room, $item) {
  return (new Key($item['name'], $item['secret']))->define(function ($key) use ($item) {
    $inspector = $key->getComponent('Inspector');
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
    imageChangerHelper($item, $key);
  });
};

/**
 * Constructs a Food object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleFood($room, $item) {
  return (new Food($item['name']))->define(function ($food) use ($item) {
    $inspector = $food->getComponent('Inspector');
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
    imageChangerHelper($item, $food);
  });
}

function imageChangerHelper($item, $object) {
  if (array_key_exists('onAssign.room.imageUrl', $item)) {
      $assignable = $object->getComponent('Assignable');
      $initialOnAssign = $assignable->popEventHandler('assign');
      $assignable->onAssign(function ($assignable, $oldTarget, $newTarget, $index) use ($initialOnAssign, $item) {
        $room = $oldTarget;
        if (is_a($room, '\game\Room'))
          $room->setImageUrl($item['onAssign.room.imageUrl']);
        return $initialOnAssign($assignable, $oldTarget, $newTarget, $index);
      });
    }
}

/**
 * Constructs a General Object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleGeneralObject($room, $item) {
  return (new GeneralObject($item['name']))->define(function ($generalObject) use ($item) {
    $inspector = $generalObject->getComponent('Inspector');
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
    imageChangerHelper($item, $generalObject);
  });
}

/**
 * Constructs a Lamp object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleLamp($room, $item) {
  return ((new Lamp($item['name']))->define(function ($lamp) use ($item) {
    $lamp->getComponent('Inspector')->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
  }));
}

/**
 * Constructs an Equipment object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleEquipment($room, $item) {
  return (new Equipment($item['name']))->define(function ($equipment) use ($item) {
    $inspector = $equipment->getComponent('Inspector');
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
    if (array_key_exists('onEquip.description', $item)) {
      $equippable = $equipment->getComponent('Equippable');
      $initialOnEquip = $equippable->popEventHandler('equip');
      $equippable->onEquip(function ($equippable) use ($item, $initialOnEquip) {
        //after equipping, update the inspect call so that it tells the rest of the story
        $equipment = $equippable->getParent();
        $inspector = $equipment->getComponent('Inspector');
        $initialOnInspect = $inspector->popEventHandler('inspect');
        $inspector->onInspect(function ($inspector) use ($item, $initialOnInspect) {
          return $initialOnInspect($inspector) . "  " . $item['onEquip.description'];
        });

        return $initialOnEquip($equippable) . "  " . $inspector->inspect();
      });
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
    $roomName = "";
    if (is_a($room, "\game\Room")) $roomName = $room->getName();
    if (is_array($room)) $roomName = $room['name'];
    if (!$this->game->getRoom($roomName)) {
      if (is_array($room)) $room = assembleRoom($room);
      $this->game->addRoom($room);
      if (!$this->game->getSpawnPoint())
        $room->define(function ($room) {
          $room->setSpawnPoint();
        });
    }
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
    if (is_a($roomName1, "\game\Room")) $roomName1 = $roomName1->getName();
    if (is_array($roomName2)) $roomName2 = $roomName2['name'];
    if (is_a($roomName2, "\game\Room")) $roomName2 = $roomName2->getName();
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

  public function insertRoomAt($existingRoom, $direction, $newRoom)
  {
    $this->insertRoom($newRoom);
    $this->connectRooms($existingRoom, $direction, $newRoom);
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
