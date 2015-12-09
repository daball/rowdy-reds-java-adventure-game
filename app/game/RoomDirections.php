<?php

namespace game;

require_once "Direction.php";

/**
 * Defines RoomDirections for a given Room
 **/
class RoomDirections
{
  public $n;
  public $s;
  public $e;
  public $w;
  public $u;
  public $d;

  public function __construct()
  {
    $this->n = new Direction(Direction::$n);
    $this->s = new Direction(Direction::$s);
    $this->e = new Direction(Direction::$e);
    $this->w = new Direction(Direction::$w);
    $this->u = new Direction(Direction::$u);
    $this->d = new Direction(Direction::$d);
  }

  public function getDirection($direction)
  {
    $direction = Direction::cardinalDirection($direction);
    switch ($direction)
    {
      case Direction::$n:
        return $this->n;
      case Direction::$s:
        return $this->s;
      case Direction::$e:
        return $this->e;
      case Direction::$w:
        return $this->w;
      case Direction::$u:
        return $this->u;
      case Direction::$d:
        return $this->d;
    }
    throw new DirectionException();
  }
}
