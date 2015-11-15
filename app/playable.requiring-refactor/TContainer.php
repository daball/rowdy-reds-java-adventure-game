<?php

namespace playable;

require_once "GameObject.php";

trait TContainer
{
  protected $items = array();
  protected $setCallback = null;
  protected $removeCallback = null;

  public function setItem($itemName, GameObject $item)
  {
    $this->items[$itemName] = $item;
    $cb = $this->setCallback;
    if ($cb)
      return $cb($itemName, $item);
    return $this;
  }
  public function onSetItem($fn)
  {
    $this->setCallback = null;
    return $this;
  }
  public function keyExists($itemName)
  {
    return array_key_exists($itemName, $this->items);
  }
  public function itemExists(GameObject $item)
  {
    return array_search($item, $this->items);
  }
  public function getItem($itemName)
  {
    return $this->items[$itemName];
  }
  public function removeItem($itemName)
  {
    unset($this->items[$itemName]);
    $cb = $this->removeCallback;
    if ($cb)
      return $cb();
    return $this;
  }
  public function onRemoveItem($fn)
  {
    $this->removeCallback = $fn;
    return $this;
  }
  public function getAllItems()
  {
    return $this->items;
  }
}
