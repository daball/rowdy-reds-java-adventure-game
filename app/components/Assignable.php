<?php

namespace components;

require_once 'BaseComponent.php';

class Assignable extends BaseComponent
{
  protected $onBeforeAssignClosure = null;
  protected $onAssignClosure = null;
  protected $onRefuseAssignClosure = null;

  public function __construct() {
    $this->define(function ($assignable) {
      $assignable->onBeforeAssign(function ($assignable, $oldTarget, $newTarget, $index) {
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
      $assignable->onAssign(function ($assignable, $oldTarget, $newTarget, $index) {
        $item = $assignable->getParent();
        return "The " . $item->getName() . " has been assigned to your " . $newTarget->getName() . ".";
        // return "You $item->getName() has replaced the $oldItem->getName() in your $newTarget->getName().";
      });
      $assignable->onRefuseAssign(function ($assignable, $oldTarget, $newTarget, $index) {
        $item = $assignable->getParent();
        return "The " . $item->getName() . " has not been assigned to your " . $newTarget->getName() . ".";
      });
    });
  }

  public function assignTo($target, $index = -1) {
    $onBeforeAssign = $this->onBeforeAssign();
    $onAssign = $this->onAssign();
    $onRefuseAssign = $this->onRefuseAssign();

    $item = $this->getParent();
    $currentContainer = $item->getContainer();
    if ($onBeforeAssign($this, $currentContainer, $target, $index))
    {
      $output = "";
      if ($currentContainer) {
        //unset existing item
        $container = $currentContainer->getComponent('Container');
        $index = $container->findIndexByItem($item);
        $output .= $container->unsetItemAt($index) . ' ';
      }
      {
        //set item
        $container = $target->getComponent('Container');
        $output .= $container->setItemAt($index, $item) . ' ';
      }
      $output .= $onAssign($this, $currentContainer, $target, $index);
      return $output;
    }
    return $onRefuseAssign($this, $currentContainer, $target, $index);
  }

  public function onBeforeAssign($closure=null) {
    if ($closure)
      $this->onBeforeAssignClosure = $this->serializableClosure($closure);
    return $this->onBeforeAssignClosure;
  }

  public function onAssign($closure=null) {
    if ($closure)
      $this->onAssignClosure = $this->serializableClosure($closure);
    return $this->onAssignClosure;
  }

  public function onRefuseAssign($closure=null) {
    if ($closure)
      $this->onRefuseAssignClosure = $this->serializableClosure($closure);
    return $this->onRefuseAssignClosure;
  }
}
