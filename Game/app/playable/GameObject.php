<?php

namespace playable;

/**
 * The GameObject represents almost any object in the game.
 * It is meant to be generic enough to be able to become any
 * particular object through its use of components. You may
 * add components to change the behavior of the GameObject.
 * If you find that you keep creating the same type of
 * GameObject instances, you may extend the GameObject class
 * and implement the creation logic in the __construct() function
 * for shrink-wrapping your specific objects.
 *
 * There are many predefined classes that extend GameObject. For
 * example, there is a Room, Door, LockedDoor, Container, Note,
 * Key, and so forth. Each of these extend GameObject and implement
 * components in a certain way in order to achieve the game behavior
 * desired.
 **/
class GameObject /*implements \Serializable*/ {
  /**
   * The name of the GameObject.
   **/
  protected $name = "";

  /**
   * @ignore
   */
  private $components;

  public function __construct($name) {
    $this->setName($name);
  }

  /**
   *
   **/
  public function getName() {
    return $this->name;
  }

  /**
   *
   **/
  public function setName($name) {
    $this->name = $name;
  }

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
