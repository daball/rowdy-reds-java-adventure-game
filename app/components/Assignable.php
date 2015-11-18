<?php

namespace components;

require_once 'BaseComponent.php';

class Assignable extends BaseComponent
{
  protected $onBeforeAssignCallback = null;
  protected $onAssignCallback = null;
  protected $onRefuseAssignCallback = null;

  protected $currentContainer = null;

  public function __construct() {
    $this->define(function ($assignable) {
      $assignable->onBeforeAssign(function ($itemName, $oldTarget, $newTarget, $index) {
        if (!$newTarget->hasComponent('Container'))
          return false;
        if ($index == -1) //index is automatic
          return true;
        if ($index >= 0)
        {
          if ($newTarget->getComponent('Container')->getMaxItems() == -1)
            return true;
          if ($index < $newTarget->getComponent('Container')->getMaxItems())
            return true;
        }
        return false;
      });
      $assignable->onAssign(function ($item, $oldTarget, $newTarget, $index) {
        return "The $item->getName() has been assigned to your $newTarget->getName()."
        // return "You $item->getName() has replaced the $oldItem->getName() in your $newTarget->getName().";
      });
      $assignable->onRefuseAssign(function ($itemName, $oldTarget, $newTarget, $index) {
        return "The $item->getName() has not been assigned to your $newTarget->getName()."
      });
    });
  }

  public function assignTo($target, $index = -1) {
    $onBeforeAssignCallback = $this->onBeforeAssignCallback;
    $onAssignCallback = $this->onAssignCallback;
    $onRefuseAssignCallback = $this->onRefuseAssignCallback;

    $item = $this->getParent();
    $currentContainer = $this->getCurrentContainer()->getComponent('Container');
    // $currentIndex = $currentContainer->
    // if ($onBeforeAssignCallback($item, $currentContainer, $target, $index))
    // {
    //   $output = "";
    //   $output .= $currentContainer->getComponent('Container')->
    //   $output .= $onAssignCallback($item, $currentContainer, $target, $index);
    //   return $output;
    // }
    // else
    //   return $onBeforeAssignCallback($item, $currentContainer, $target, $index)
  }

  public function onBeforeAssign($callback) {
    $this->onBeforeAssignCallback = $this->serializableClosure($callback);
  }

  public function onAssign($callback) {
    $this->onAssignCallback = $this->serializableClosure($callback);
  }

  public function onRefuseAssign($callback) {
    $this->onRefuseAssignCallback = $this->serializableClosure($callback);
  }
}
