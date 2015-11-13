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
    $this->items = array();
    $this->validItemTypes = array('\playable\GameObject');
    $this->onBeforeSet(function ($container, $index, $item) {
      return (
              //If index is between 0 and maxItems-1 (when maxItems >= 0)
              ($index >= 0 &&
                ($this->maxItems < 0 || $index < $container->maxItems)) &&
              //And if the item is a valid item type
              ($container->isItemAValidType($item)) &&
              //And if the item has a
              ($container->isItemAssignable($item))
              ;
    });
    $this->onSet(function ($container, $index, $item) {
      return "You assigned an item to a container.";
    });
    $this->onRefuseSet(function ($container, $index, $item) {
      if ($index < 0 && $index >= $container->maxItems) {
        return "You cannot assign an item there, the index is out of bounds.";
      }
      else {
        return "You tried to assign an item, but it did not work.";
      }
    });
    $this->onBeforeUnset(function ($container, $index, $item) {
      //Not sure why not
      return true;
    });
    $this->onUnset(function ($container, $index, $item) {
      return "You unassigned an"
    });
  }

  public function setItemAt($index, $item) {
    $onBeforeSetCallback = $this->onBeforeSetCallback;
    $onSetCallback = $this->onSetCallback;
    $onRefuseSetCallback = $this->onRefuseSetCallback;

    if ($onBeforeSetCallback($index, $item)) {
      $this->items[$index] = $item;
      return $onSetCallback($index, $item);
    }
    else
      return $onRefuseSetCallback($index, $item);
  }

  public function unsetItemAt($index) {
    $this->items[$index] = $item;
  }

  public function hasItemAt($index) {
    return isset(isset($this->items[$i]));
  }

  public function getItemAt($index) {
    if ($this->hasItemAt($index))
      return $this->items[$index];
    return null;
  }

  public function getMaxItems() {
    return $this->maxItems;
  }

  public function countItems() {
    return array_count_values($this->items);
  }

  public function findItemIndexByName($gameObjectName) {
    for ($i = 0; $i < sizeof($this->items); $i++) {
      if ($this->hasItemAt($i) &&
          $this->getItemAt($i)->getName() == $gameObjectName)
          return $i;
    }
    return -1;
  }

  public function findItemByName($gameObjectName) {
    $i = $this->findItemIndexByName;
    if ($i > -1)
      return $this->getItemAt($i);
    return null;
  }

  public function isItemAValidType($item) {
    for($v = 0; $v < sizeof($this->validItemTypes); $v++)
      if (is_a($item, $this->validItemTypes[$v]))
        return true;
    return false;
  }
}
