<?php

///Helper class helps resolve directions to a direction string
///for proper use in Room->directions associative array.
class Direction
{
  public static $north = 'n';
  public static $south = 's';
  public static $east = 'e';
  public static $west = 'w';

  public static $n = 'n';
  public static $s = 's';
  public static $e = 'e';
  public static $w = 'w';

  public static function getDirection($direction)
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
    }
  }

  public static function oppositeDirection($direction)
  {
    switch (self::getDirection($direction))
    {
      case self::$n:
        return self::$s;
      case self::$s:
        return self::$n;
      case self::$e:
        return self::$w;
      case self::$w:
        return self::$e;
    }
  }
}