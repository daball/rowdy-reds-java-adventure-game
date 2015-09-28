<?php

namespace playable;

require_once "GameObject.php";
require_once "IUnlockable.php";

interface IContainer
{
  public function setItem($itemName, GameObject $item);
  public function onSetItem($fn);
  public function keyExists($itemName);
  public function itemExists(GameObject $item);
  public function getItem($itemName);
  public function removeItem($itemName);
  public function onRemoveItem($fn);
  public function getAllItems();
}
