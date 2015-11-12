<?php

namespace map;

require_once "Direction.php";

/**
 * Defines RoomDirections for a given Room
 **/
class RoomDirections implements \Serializable
{
  public $n;
  public $s;
  public $e;
  public $w;

  public function __construct()
  {
    $this->n = new Direction();
    $this->s = new Direction();
    $this->e = new Direction();
    $this->w = new Direction();
  }

  public function getDirection($direction)
  {
    $direction = Direction::cardinalDirection($direction);
    switch ($direction)
    {
      case "n":
        return $this->n;
      case "s":
        return $this->s;
      case "e":
        return $this->e;
      case "w":
        return $this->w;
    }
  }

  public function serialize() {
    return serialize(
      array(
        'n' => $this->n,
        's' => $this->s,
        'e' => $this->e,
        'w' => $this->w,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->n = $data['n'];
    $this->s = $data['s'];
    $this->e = $data['e'];
    $this->w = $data['w'];
  }
}
