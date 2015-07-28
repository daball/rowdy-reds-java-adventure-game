<?php

///Helper class used to store data associated with each direction in the
///associative array for each room in the map.
class RoomDirection
{
  public $description = "";
  public $jumpTo = "";

  public function serialize() {
    return serialize(
      array(
        'description' => $this->description,
        'jumpTo' => $this->jumpTo
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->description = $data['description'];
    $this->jumpTo = $data['jumpTo'];
  }
}
