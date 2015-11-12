<?php

namespace components;

require_once __DIR__.'/../playable/GameObject.php';

use \playable\GameObject;

/**
 * @ignore
 */
abstract class BaseComponent
{
  /**
   * @ignore
   */
  private $parent;

  /**
   * @ignore
   */
  public function getParent() {
    return $this->parent;
  }

  /**
   * @ignore
   */
  public function setParent(GameObject $parent) {
    //assert $parent is the right type
    if (!is_a($parent, "\playable\GameObject")) {
      throw new \Exception('You must pass a \playable\GameObject instance or an object from a class extended from \playable\GameObject in order to call \components\BaseComponent\setParent().');
    }
    $this->parent = $parent;
    return $this->getParent();
  }
}
