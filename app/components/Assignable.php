<?php

namespace components;

require_once 'BaseComponent.php';

class Assignable extends BaseComponent
{
  protected $onBeforeAssignClosure = null;
  protected $onAssignClosure = null;
  protected $onRefuseAssignClosure = null;

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
        return "The $item->getName() has been assigned to your $newTarget->getName().";
        // return "You $item->getName() has replaced the $oldItem->getName() in your $newTarget->getName().";
      });
      $assignable->onRefuseAssign(function ($itemName, $oldTarget, $newTarget, $index) {
        return "The $item->getName() has not been assigned to your $newTarget->getName().";
      });
    });
  }

  public function assignTo($target, $index = -1) {
    $onBeforeAssign = $this->onBeforeAssign();
    $onAssign = $this->onAssign();
    $onRefuseAssign = $this->onRefuseAssign();

    $item = $this->getParent();
    $currentContainer = $this->getCurrentContainer();
    if ($currentContainer) {
      if ($onBeforeAssign($item, $currentContainer, $target, $index))
      {
        $output = "";
        {//unset existing item
          $container = $currentContainer->getComponent('Container');
          $index = $container->findIndexByItem($item);
          $output .= $container->unsetItemAt($currentIndex) . ' ';
        }
        {//set item
          $container = $target->getComponent('Container');
          $output .= $container->getComponent('Container')->setItemAt($item, $index) . ' ';
        }
        $output .= $onAssign($item, $currentContainer, $target, $index);
        return $output;
      }
    }
    return $onRefuseAssign($item, $currentContainer, $target, $index);
  }

  public function onBeforeAssign($closure) {
    if ($closure)
      $this->onBeforeAssignClosure = $this->serializableClosure($closure);
    return $this->onBeforeAssignClosure;
  }

  public function onAssign($closure) {
    if ($closure)
      $this->onAssignClosure = $this->serializableClosure($closure);
    return $this->onAssignClosure;
  }

  public function onRefuseAssign($closure) {
    if ($closure)
      $this->onRefuseAssignClosure = $this->serializableClosure($closure);
    return $this->onRefuseAssignClosure;
  }
}
