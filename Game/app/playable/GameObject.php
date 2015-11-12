<?php

namespace playable;

class GameObject /*implements \Serializable*/ {
  /**
   * @ignore
   */
  private $components;

  /**
   * @ignore
   */
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
  
  /**
   * @ignore
   */
  public function hasComponent($componentType) {
    return array_key_exists($componentType, $this->components);
  }

  /**
   * @ignore
   */
  public function getComponent($componentType) {
    //return the component
    if ($this->hasComponent($componentType))
      return $this->components[$componentType];
  }

  /**
   * @ignore
   */
  public function removeComponent($componentType) {
    unset($this->components[$componentType]);
  }
}
