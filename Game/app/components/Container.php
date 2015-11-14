<?php

namespace components;

require_once 'BaseComponent.php';

class Container extends BaseComponent
{
  /**
   * items is an array of GameObjects.
   **/
  protected $items = null;
  /**
   * maxItems is the maximum number of GameObjects that items can store, per
   * the game rules. If maxItems < 0, then you can fill items as full as you
   * want.
   **/
  protected $maxItems = -1;
  /**
   * validItemTypes is an array of GameObject types. It defaults to:
   * [ '\playable\GameObject' ], but can be set so that only particular
   * objects can be inserted into the container.
   **/
  protected $validItemTypes = null;

  protected $onBeforeSetCallback = null;
  protected $onSetCallback = null;
  protected $onRefuseSetCallback = null;

  protected $onBeforeUnsetCallback = null;
  protected $onUnsetCallback = null;
  protected $onRefuseUnsetCallback = null;

  public function __construct() {
    $this->define(function ($container) {
      $container->maxItems = -1;
      $container->items = array();
      $container->validItemTypes = array('\playable\GameObject');
      $setLogic = function ($container, $index, $item) {
        return (
                //If index is between 0 and maxItems-1 (when maxItems >= 0)
                ($index >= 0
                  && ($container->getMaxItems() < 0
                      || $index < $container->getMaxItems()))
                //And if the index is not out of bounds
                && (!$container->isIndexOutOfBounds($index))
                //And if the item is a valid item type
                && ($container->isItemAValidType($item))
                //And if the item has an Assignable component
                && ($container->isItemAssignable($item))
              );
      };
      $container->onBeforeSet($setLogic);
      $container->onSet(function ($container, $index, $item) {
        return "You assigned an item to a container.";
      });
      $container->onRefuseSet(function ($container, $index, $item) {
        if (!$container->isItemAValidType($item)) {
          return "You cannot put this type of item in the container.";
        }
        else if (!$container->isItemAssignable($item)) {
          return "You cannot assign this item.";
        }
        else if ($container->isIndexOutOfBounds($index)) {
          return "You cannot assign an item there, the index is out of bounds.";
        }
        else {
          return "You tried to assign an item, but it did not work.";
        }
      });
      $container->onBeforeUnset(function ($container, $index, $item) use ($setLogic) {
        //Not sure why not
        return $setLogic($container, $index, $item) && $item != null;
      });
      $container->onUnset(function ($container, $index, $item) {
        return "You unassigned an item from a container.";
      });
      $container->onRefuseUnset(function ($container, $index, $item) {
        //Not sure why not
        if ($item == null)
          return "There was no item to unset from the container.";
        return "You attempted to unset an item from the container, but it was not unset.";
      });
    });
  }

  public function insertItem($item) {
    $index = count($this->items);
    while ($this->hasItemAt($index)) { $index++; }
    return $this->setItemAt($index, $item);
  }

  public function setItemAt($index, $item) {
    $onBeforeSetCallback = $this->onBeforeSetCallback;
    $onSetCallback = $this->onSetCallback;
    $onRefuseSetCallback = $this->onRefuseSetCallback;

    if ($onBeforeSetCallback($this, $index, $item)) {
      $this->items[$index] = $item;
      return $onSetCallback($this, $index, $item);
    }
    else
      return $onRefuseSetCallback($this, $index, $item);
  }

  public function unsetItemAt($index) {
    $onBeforeUnsetCallback = $this->onBeforeUnsetCallback;
    $onUnsetCallback = $this->onUnsetCallback;
    $onRefuseUnsetCallback = $this->onRefuseUnsetCallback;
    $item = $this->getItemAt($index);

    if ($onBeforeUnsetCallback($this, $index, $item)) {
      unset($this->items[$index]);
      return $onUnsetCallback($this, $index, $item);
    }
    else
      return $onRefuseUnsetCallback($this, $index, $item);
  }

  public function hasItemAt($index) {
    return $index >= 0 && isset($this->items[$index]);
  }

  public function getAllItems() {
    return $this->items;
  }

  public function getItemAt($index) {
    if ($this->hasItemAt($index))
      return $this->items[$index];
    return null;
  }

  public function getMaxItems() {
    return $this->maxItems;
  }

  public function setMaxItems($maxItems) {
    $this->maxItems = $maxItems;
  }

  public function countItems() {
    return count($this->items);
  }

  public function findItemIndexByName($gameObjectName) {
    for ($i = 0; $i < count($this->items); $i++) {
      if ($this->hasItemAt($i) &&
          $this->getItemAt($i)->getName() == $gameObjectName)
          return $i;
    }
    return -1;
  }

  public function findItemByName($gameObjectName) {
    $i = $this->findItemIndexByName($gameObjectName);
    if ($i > -1)
      return $this->getItemAt($i);
    return null;
  }

  public function findIndexByItem($item) {
    return array_search($item, $this->items);
  }

  public function itemExists($item) {
    return $this->findIndexByItem($item) !== FALSE;
  }

  public function isItemAValidType($item) {
    for($v = 0; $v < count($this->validItemTypes); $v++)
      if (is_a($item, $this->validItemTypes[$v]))
        return true;
    return false;
  }

  public function isItemAssignable($item) {
    return $this->isItemAValidType($item) && $item->hasComponent("Assignable");
  }

  public function isIndexOutOfBounds($index) {
    return ($index < 0
      || ($this->maxItems >= 0 && $index >= $this->maxItems));
  }

  /* Event Callback Registration Functions */

  public function onBeforeSet($callback) {
    $this->onBeforeSetCallback = $callback;
  }

  public function onSet($callback) {
    $this->onSetCallback = $callback;
  }

  public function onRefuseSet($callback) {
    $this->onRefuseSetCallback = $callback;
  }

  public function onBeforeUnset($callback) {
    $this->onBeforeUnsetCallback = $callback;
  }

  public function onUnset($callback) {
    $this->onUnsetCallback = $callback;
  }

  public function onRefuseUnset($callback) {
    $this->onRefuseUnsetCallback = $callback;
  }
}
