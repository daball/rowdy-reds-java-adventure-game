<?php

namespace playable;

require_once "GameObject.php";

trait TContainer
{
  public $items = array();
  public function addItem($itemName, GameObject $item)
  {
    $this->items[$itemName] = $item;
    return $this;
  }
  public function keyExists($itemName)
  {
    return array_key_exists($itemName, $this->items);
  }
  public function itemExists(GameObject $item)
  {
    return in_array($item, $this->items);
  }
  public function getItem($itemName)
  {
    return $this->items[$itemName];
  }
  public function removeItem($itemName)
  {
    unset($this->items[$itemName]);
    return $this;
  }
}
