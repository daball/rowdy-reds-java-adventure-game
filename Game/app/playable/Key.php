<?php

namespace playable;

require_once __DIR__.'/../util/ISerializable.php';
require_once 'GameObject.php';
require_once 'IAssignable.php';
require_once 'IInspectable.php';
require_once 'TCreateWithKey.php';
require_once 'TInspectable.php';
require_once 'TAssignable.php';
require_once __DIR__.'/../util/ISerializable.php';

/**
 * A Key item must be used to open an Unlockable item.
 */
class Key extends GameObject implements IInspectable, IAssignable, \util\ISerializable, \Serializable
{
  use TCreateWithKey;
  use TInspectable;
  use TAssignable;

  /**
   * @ignore
   */
  private $key;

  public function __construct($key) {
    $this->key = $key;
    $this->setDescription("You have found a key.");
  }

  /**
   * @ignore
   **/
  public function getKey() {
    return $this->key;
  }

  public function serialize() {
    return serialize(
      array(
        'description' => $this->description,
        'key' => $this->key,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->key = $data['key'];
    $this->__construct($this->key);
    $this->description = $data['description'];
  }
}
