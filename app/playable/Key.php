<?php

namespace playable;

require_once __DIR__.'/../game/GameObject.php';
require_once __DIR__.'/../components/Assignable.php';
require_once __DIR__.'/../components/Inspector.php';

use \game\GameObject;
use \components\Assignable;
use \components\Inspector;

/**
 * A Key item must be used to open an Unlockable item.
 */
class Key extends GameObject
{
  /**
   * @ignore
   */
  private $secret;

  /**
   * @ignore
   */
  public function __construct($name, $secret) {
    parent::__construct($name);
    $this->define(function ($key) use ($secret) {
      $key->setSecret($secret);
    });
    $this->define(function ($key) {
      $assignable = new Assignable();
      $key->addComponent($assignable);
    });
    $this->define(function ($key) {
      $inspector = new Inspector();
      $inspector->onInspect(function ($inspector) {
        return "It's a key of some sort.";
      });
      $key->addComponent($inspector);
    });
  }

  /**
   * @ignore
   **/
  public function setSecret($secret) {
    $this->secret = $secret;
  }

  /**
   * @ignore
   **/
  public function getSecret() {
    return $this->secret;
  }
}
