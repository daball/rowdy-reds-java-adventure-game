<?php

namespace components;

require_once 'BaseComponent.php';
require_once __DIR__.'/../playable/Key.php';

use \playable\Key;

/**
 * The Lockable
 * @author David Ball
 * @ignore
 **/
class Lockable extends BaseComponent
{
  protected $locked = true;
  protected $key = null;

  public function __construct(Key $key) {
    $this->key = $key;
  }

  public function getKey() {
    return $this->key;
  }

  public function lock(Key $key) {
    if ($this->getKey()->getKey() == $key->getKey())
      $this->locked = true;
  }

  public function isLocked() {
    return $this->locked;
  }

  public function setLocked() {
    $this->locked = true;
  }

  public function setUnlocked() {
    $this->locked = false;
  }
}
