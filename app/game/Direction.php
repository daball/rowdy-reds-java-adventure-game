<?php

namespace game;

require_once 'GameObject.php';
require_once __DIR__.'/../components/Inspector.php';

use \components\Inspector;
use \Exception;

/**
 * Direction stores direction information within a Room. It also provides
 * static methods for helping to sanitize and resolve directions based on
 * variable text input.
 *
 * @author David Ball
 **/
class Direction extends GameObject
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
  }

  protected $nextRoomName = "";
  protected $obvious = true;

  public function __construct($name) {
    $name = self::fullDirection($name);
    parent::__construct($name);
    $this->define(function ($direction) {
      $inspector = new Inspector();
      $inspector->onInspect(function ($inspector) {
        $direction = $inspector->getParent();
        // echo "INSPECTOR:";var_dump($inspector);
        // echo "DIRECTION:";var_dump($direction);
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

    });
    // var_dump($this);
  }

  public function getNextRoomName() {
    return $this->nextRoomName;
  }

  public function setNextRoomName($nextRoomName) {
    $this->nextRoomName = $nextRoomName;
  }

  public function setNextRoom($nextRoom) {
    if ($nextRoom == null)
      throw new Exception('Room does not exist.');
    $this->nextRoomName = $nextRoom->getName();
  }

  public function isNextRoomObvious() {
    return $this->nextRoomName && $this->obvious;
  }

  public function setNextRoomObvious($obvious = true) {
    $this->obvious = $obvious;
  }
}
