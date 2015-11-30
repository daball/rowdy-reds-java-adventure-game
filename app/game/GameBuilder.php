<?php

namespace game;

require_once 'Game.php';
require_once 'Direction.php';
require_once 'Room.php';
require_once __DIR__.'/../components/index.php';
require_once __DIR__.'/../playable/index.php';

use \Exception;
use \components\Assignable;
use \components\Container;
use \components\Puzzle;
use \playable\BasicContainer;
use \playable\Key;
use \playable\Food;
use \playable\Door;
use \playable\Equipment;
use \playable\LockedDoor;
use \playable\Dog;
use \playable\Lamp;
use \playable\LockableContainer;
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
    $imageUrl = array_key_exists('imageUrl', $meta) ? $meta['imageUrl'] : 'null.png';
    $items = array_key_exists('items', $meta) ? $meta['items'] : array();
    $dark = array_key_exists('dark', $meta) ? $meta['dark'] : false;

    $room->setImageUrl($imageUrl);
    $room->setDark($dark);
    $inspector = $room->getComponent("Inspector");
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($description) {
      $room = $inspector->getParent();
      if ($room->isDark())
        return "The room is so dark you can't see anything.";
      else
        return $description;
    });
    $container = $room->getComponent("Container");
    $container = assembleItemsIntoContainer($meta, $room, $items);

    if ($dark) {
      $room->subscribe('Lamp', function ($sender, $queue, $message) use ($room, $imageUrl) {
        if ($message == "wind") {
          $room->setDark(false);
        }
      });
      $room->subscribe('Lamp', function ($sender, $queue, $message) use ($room) {
        if ($message == "unwind") {
          $room->setDark(true);
        }
      });
    }
  });
}

function assembleItemsIntoContainer($roomDefinition, $gameObject, $items) {
  $container = $gameObject->getComponent("Container");
  foreach ($items as $item) {
    switch ($item['type']) {
      case 'note':
        $container->insertItem(assembleNote($roomDefinition, $gameObject, $item));
        break;
      case 'door':
        $container->insertItem(assembleDoor($roomDefinition, $gameObject, $item));
        break;
      case 'lockedDoor':
        $container->insertItem(assembleLockedDoor($roomDefinition, $gameObject, $item));
        break;
      case 'key':
        $container->insertItem(assembleKey($roomDefinition, $gameObject, $item));
        break;
      case 'food':
        $container->insertItem(assembleFood($roomDefinition, $gameObject, $item));
        break;
      case 'lamp':
        $container->insertItem(assembleLamp($roomDefinition, $gameObject, $item));
        break;
      case 'equipment':
        $container->insertItem(assembleEquipment($roomDefinition, $gameObject, $item));
        break;
      case 'backpack':
        $container->insertItem(assembleBackpack($roomDefinition, $gameObject, $item));
        break;
      case 'lockableContainer':
        $container->insertItem(assembleLockableContainer($roomDefinition, $gameObject, $item));
        break;
      case 'generalObject':
        $container->insertItem(assembleGeneralObject($roomDefinition, $gameObject, $item));
        break;
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
function assembleNote($roomDefinition, $room, $item) {
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
function assembleDoor($roomDefinition, $room, $item) {
  return (new Door($item['name'], $item['direction']));
}

/**
 * Constructs a LockedDoor object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleLockedDoor($roomDefinition, $room, $item) {
  return (new LockedDoor($item['name'], $item['direction'], $item['secret']));
}

/**
 * Constructs a Key object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleKey($roomDefinition, $room, $item) {
  return (new Key($item['name'], $item['secret']))->define(function ($key) use ($roomDefinition, $room, $item) {
    $inspector = $key->getComponent('Inspector');
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
    imageChanger($roomDefinition, $room, $item, $key);
  });
};

/**
 * Constructs a Food object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleFood($roomDefinition, $room, $item) {
  return (new Food($item['name']))->define(function ($food) use ($roomDefinition, $room, $item) {
    $inspector = $food->getComponent('Inspector');
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
    imageChanger($roomDefinition, $room, $item, $food);
  });
}

/**
 * Constructs a Lockable Container based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleLockableContainer($roomDefinition, $room, $item) {
  return (new LockableContainer($item['name'], $item['secret']))->define(function ($lockableContainer) use ($roomDefinition, $room, $item) {
    $inspector = $lockableContainer->getComponent('Inspector');
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($item) {
      if ($inspector->getParent()->getComponent("Openable")->isOpened()) {
        $items = $inspector->getParent()->getComponent('Container')->getAllItems();
        $saItems = array();
        foreach($items as $value) { array_push($saItems, insertAOrAn($value->getName())); }
        if (count($roomItems) > 0) {
          $sItems = natural_language_join($saItems);
          $output .= "  You see here $sItems.";
        }
        return $item['description'] . $output;
      }
      else
        return $item['description'];
    });
    imageChanger($roomDefinition, $room, $item, $lockableContainer);
    $container = $room->getComponent("Container");
    $container = assembleItemsIntoContainer($item, $lockableContainer, $item['items']);
  });
}

/**
 * Constructs a General Object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleGeneralObject($roomDefinition, $room, $item) {
  return (new GeneralObject($item['name']))->define(function ($generalObject) use ($roomDefinition, $room, $item) {
    $inspector = $generalObject->getComponent('Inspector');
    $inspector->popEventHandler('inspect');
    $inspector->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
    imageChanger($roomDefinition, $room, $item, $generalObject);
  });
}

/**
 * Constructs a Lamp object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleLamp($roomDefinition, $room, $item) {
  return ((new Lamp($item['name']))->define(function ($lamp) use ($roomDefinition, $room, $item) {
    $lamp->getComponent('Inspector')->onInspect(function ($inspector) use ($item) {
      return $item['description'];
    });
    imageChanger($roomDefinition, $room, $item, $lamp);
  }));
}

/**
 * Constructs an Equipment object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleEquipment($roomDefinition, $room, $item) {
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
          return $item['onEquip.description'];
        });
        return /*$initialOnEquip($equippable) . "  " .*/ $inspector->inspect();
      });
    }
  });
}

/**
 * Constructs an Equipment object based on the item definition.
 *
 * @param $room Room instance.
 * @param $item Item definition (associative array)
 **/
function assembleBackpack($roomDefinition, $room, $item) {
  return assembleEquipment($roomDefinition, $room, $item)->define(function ($equipment) use ($item) {
    $container = new Container();
    $container->setMaxItems(5);
    $equipment->addComponent($container);
    $equippable = $equipment->getComponent('Equippable');
    $initialOnEquip = $equippable->popEventHandler('equip');
    $equippable->onEquip(function ($equippable) use ($item, $initialOnEquip) {
      $equipOutput = $initialOnEquip($equippable);
      $equipment = $equippable->getParent();
      $inspector = $equipment->getComponent('Inspector');
      $initialOnInspect = $inspector->popEventHandler('inspect');
      $inspector->onInspect(function ($inspector) use ($item, $initialOnInspect) {
        $container = $inspector->getParent()->getComponent("Container");
        $output = "";
        foreach ($container->getAllItems() as $key => $value) {
          $output .= "  backpack[$key] = ";
          if ($value) $output .= $value->getName() . ";";
          else $output .= "null;";
          $output .= "\n";
        }
        $output = $initialOnInspect($inspector) . "  The contents of your backpack are:\n" . $output;
        return $output;
      });
      return $equipOutput;
    });
  });
}

function imageChanger($roomDefinition, $room, $item, $gameObject) {
  if (array_key_exists('onAssign.room.imageUrl', $item)) {
    $assignable = $gameObject->getComponent('Assignable');
    $initialOnAssign = $assignable->popEventHandler('assign');
    $assignable->onAssign(function ($assignable, $oldTarget, $newTarget, $index) use ($roomDefinition, $room, $initialOnAssign, $item) {
      if (is_a($oldTarget, '\game\Room') && $oldTarget->getImageUrl() == $roomDefinition['imageUrl'])
        $oldTarget->setImageUrl($item['onAssign.room.imageUrl']);
      else if (is_a($newTarget, '\game\Room') && $newTarget->getImageUrl() == $item['onAssign.room.imageUrl'])
        $newTarget->setImageUrl($roomDefinition['imageUrl']);
      return $initialOnAssign($assignable, $oldTarget, $newTarget, $index);
    });
  }
  if (array_key_exists('onOpen.room.imageUrl', $item)) {
    $openable = $gameObject->getComponent('Openable');
    $initialOnOpen = $openable->popEventHandler('open');
    $openable->onOpen(function ($openable) use ($roomDefinition, $room, $initialOnOpen, $item) {
      if ($openable->isOpened())
        $room->setImageUrl($item['onOpen.room.imageUrl']);
      else
        $room->setImageUrl($roomDefinition['imageUrl']);
      return $initialOnOpen($openable);
    });
  }
};


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
  
  public function oneWayConnectRoom($roomName1, $room1Direction, $roomName2)
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
    $room1->getDirection($room1Direction)->setNextRoom($room2);
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
