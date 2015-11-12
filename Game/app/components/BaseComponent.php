<?php

namespace components;

require_once __DIR__.'/../playable/GameObject.php';

use \playable\GameObject;

abstract class BaseComponent
{
  private $parent;

  public function getParent() {
    return $this->parent;
  }

  public function setParent(GameObject $parent) {
    $this->parent = $parent;
    return $this->getParent();
  }
}
