<?php

namespace playable;

class GameObject /*implements \Serializable*/ {
  private $components;

  public function addComponent($component) {
    //assert $component is the right type
    if (!is_a($component, "\components\BaseComponent")) {
      throw new \Exception('You must pass an object from a class extended from \components\BaseComponent in order to call \playable\GameObject\addComponent().');
    }
    //register this GameObject into the component as its parent, in case
    //the Component needs to be able use the GameObject it belongs to
    $component->setParent($this);
    //use reflection to obtain the class type name, without the namespace,
    //use this as the associative array key for getComponent()
    $reflectComponent = new \ReflectionClass($component);
    //store the component
    $this->components[$reflectComponent->getShortName()] = $component;
  }

  public function getComponent($componentType) {
    //return the component
    return $this->components[$componentType];
  }

  public function removeComponent($componentType) {
    unset($this->components[$componentType]);
  }
}
