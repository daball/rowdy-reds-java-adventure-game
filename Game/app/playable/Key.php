<?php

namespace playable;

require_once __DIR__.'/../util/ISerializable.php';
require_once 'GameObject.php';
require_once 'IAssignable.php';
require_once 'IInspectable.php';
require_once 'TCreateWithKey.php';

/**
 * A Key item must be used to open an Unlockable item.
 */
class Key extends GameObject implements IInspectable, IAssignable, \util\ISerializable
{
  use TCreateWithKey;

  /**
   * @ignore
   */
  private $key;

  public $description = "You have found a key.";

  public function __construct($key) {
    $this->key = $key;
  }

  /**
   * @ignore
   **/
  public function getkey() {
    return $this->key;
  }

  public function serialize() {
    return serialize(
      array(
        'key' => $this->key
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->key = $data['key'];
  }

  public function inspect() {
    return "Here lies a key.";
  }
}
