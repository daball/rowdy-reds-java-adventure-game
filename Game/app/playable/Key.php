<?php

namespace playable;

require_once 'GameObject.php';

/**
 * A Key item must be used to open an Unlockable item.
 */
class Key extends GameObject
{
  /**
   * @ignore
   */
  private $key;

  /**
   * @ignore
   */
  public function __construct($key) {
    $this->key = $key;
  }

  /**
   * @ignore
   **/
  public function getKey() {
    return $this->key;
  }

  /**
   * @ignore
   */
  public function serialize() {
    return serialize(
      array(
        'key' => $this->key,
      )
    );
  }

  /**
   * @ignore
   */
  public function unserialize($data) {
    $data = unserialize($data);
    $this->key = $data['key'];
    $this->__construct($this->key);
  }
}
