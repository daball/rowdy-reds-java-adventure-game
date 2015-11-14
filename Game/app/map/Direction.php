<?php

namespace map;

require_once __DIR__.'/../playable/GameObject.php';
require_once __DIR__.'/../components/Inspector.php';

use \playable\GameObject;
use \components\Inspector;

/**
 * Direction stores direction information within a Room. It also provides
 * static methods for helping to sanitize and resolve directions based on
 * variable text input.
 *
 * @author David Ball
 **/
class Direction extends GameObject //implements \Serializable
{
  public static $north = 'n';
  public static $south = 's';
  public static $east = 'e';
  public static $west = 'w';
  public static $up = 'u';
  public static $down = 'd';

  public static $n = 'n';
  public static $s = 's';
  public static $e = 'e';
  public static $w = 'w';
  public static $u = 'u';
  public static $d = 'd';

  public static function cardinalDirection($direction)
  {
    switch (strtolower($direction))
    {
      case "north":
      case "n":
        return self::$n;
      case "south":
      case "s":
        return self::$s;
      case "east":
      case "e":
        return self::$e;
      case "west":
      case "w":
        return self::$w;
      case "up":
      case "u":
        return self::$u;
      case "down":
      case "d":
        return self::$d;
    }
    throw new DirectionException();
  }

  public static function oppositeDirection($direction)
  {
    switch (self::cardinalDirection($direction))
    {
      case self::$n:
        return self::$s;
      case self::$s:
        return self::$n;
      case self::$e:
        return self::$w;
      case self::$w:
        return self::$e;
      case self::$u:
        return self::$d;
      case self::$d:
        return self::$u;
    }
    throw new DirectionException();
  }

  public static function fullDirection($direction)
  {
    switch (self::cardinalDirection($direction))
    {
      case self::$n:
        return "north";
      case self::$s:
        return "south";
      case self::$w:
        return "west";
      case self::$e:
        return "east";
      case self::$u:
        return "up";
      case self::$d:
        return "down";
    }
    throw new DirectionException();
  }

  protected $nextRoom = "";
  protected $obvious = true;

  public function __construct($name) {
    $name = self::cardinalDirection($name);
    parent::__construct($name);
    /*$this->define(function ($direction) {
      $inspector = new Inspector();
      $inspector->onInspect(function ($inspector) {
        $direction = $inspector->getParent();
        $name = Direction::fullDirection($direction->getName());
        $nextRoom = $direction->getNextRoomName();
        if ($direction->isNextRoomObvious()) {
          if ($nextRoom)
            return "To your $name, there is a $nextRoom.";
          else
            return "You cannot go $name.";
        }
        else
          return "You wonder what is to your $name.";
      });
      $direction->addComponent($inspector);
    });*/
  }

  public function getNextRoomName() {
    return $this->nextRoom;
  }

  public function getNextRoom() {
    return $this->nextRoom;
  }

  public function setNextRoomName($nextRoomName) {
    $this->nextRoom = $nextRoomName;
  }

  public function setNextRoom(Room $nextRoom) {
    $this->nextRoom = $nextRoom->getName();
  }

  public function isNextRoomObvious() {
    return $this->obvious;
  }

  public function setNextRoomObvious() {
    $this->obvious = true;
  }

  public function setNextRoomNotObvious() {
    $this->obvious = false;
  }

  // public function serialize() {
  //   return serialize(
  //     array(
  //       'nextRoom' => $this->nextRoom,
  //       'obvious' => $this->obvious,
  //     )
  //   );
  // }
  //
  // public function unserialize($data) {
  //   $data = unserialize($data);
  //   $this->nextRoom = $data['nextRoom'];
  //   $this->obvious = $data['obvious'];
  // }
}
