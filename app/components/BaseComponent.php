<?php

namespace components;

require_once __DIR__.'/../util/TDefine.php';
require_once __DIR__.'/../util/TSerializableClosure.php';

use \util\TDefine;
use \util\TSerializableClosure;

/**
 * @ignore
 */
abstract class BaseComponent
{
  use TDefine;
  use TSerializableClosure;

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
  public function setParent($parent) {
    //assert $parent is the right type
    if (!is_a($parent, "\game\GameObject")) {
      throw new \Exception('You must pass a \game\GameObject instance or an object from a class extended from \game\GameObject in order to call \components\BaseComponent\setParent().');
    }
    $this->parent = $parent;
    return $this->getParent();
  }
}
