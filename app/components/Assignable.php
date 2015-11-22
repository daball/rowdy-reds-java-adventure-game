<?php

namespace components;

require_once 'BaseComponent.php';

class Assignable extends BaseComponent
{
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
        $container = $item->getContainer();
        $yoursOrTheirs = "the";
        if ($container && ($container->getName() == 'leftHand' || $container->getName() == 'rightHand' || $container->getName() == 'backpack'))
          $yoursOrTheirs = "your";
        return "The " . $item->getName() . " has been assigned to $yoursOrTheirs " . $newTarget->getName() . ".";
        // return "You $item->getName() has replaced the $oldItem->getName() in your $newTarget->getName().";
      });
      $assignable->onRefuseAssign(function ($assignable, $oldTarget, $newTarget, $index) {
        $item = $assignable->getParent();
        return "The " . $item->getName() . " has not been assigned to your " . $newTarget->getName() . ".";
      });
    });
  }

  public function assignTo($target, $index = -1) {
    $item = $this->getParent();
    $currentContainer = $item->getContainer();
    if ($this->trigger('beforeAssign', array($this, $currentContainer, $target, $index)))
    {
      $output = "";
      if ($currentContainer) {
        //unset existing item
        $container = $currentContainer->getComponent('Container');
        $index = $container->findIndexByItem($item);
        $output = $container->unsetItemAt($index) . ' ';
      }
      {
        //set item
        $container = $target->getComponent('Container');
        $output .= $container->setItemAt($index, $item) . ' ';
      }
      $output = $this->trigger('assign', array($this, $currentContainer, $target, $index)) . ' ' . $output;
      return trim($output);
    }
    return $this->trigger('refuseAssign', array($this, $currentContainer, $target, $index));
  }

  public function onBeforeAssign($closure=null) {
    return $this->on("beforeAssign", $closure);
  }

  public function onAssign($closure=null) {
    return $this->on("assign", $closure);
  }

  public function onRefuseAssign($closure=null) {
    return $this->on("refuseAssign", $closure);
  }
}
