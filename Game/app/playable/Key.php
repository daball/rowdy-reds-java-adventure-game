<?php

namespace playable;

require_once __DIR__.'/../util/ISerializable.php';

/**
 * A Key item must be used to open an Unlockable item.
 */
class Key implements IInspectable, \util\ISerializable
{
  /**
   * @ignore
   */
  private $keyID;

  public $description = "You have found a key.";

  public function __construct($keyID) {
    $this->keyID = $keyID;
  }

  /**
   * @ignore
   **/
  public function getKeyID() {
    return $this->keyID;
  }

  public function serialize() {
    return serialize(
      array(
        'keyID' => $this->keyID
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->keyID = $data['keyID'];
  }

  public function inspect() {
    return "Here lies a key.";
  }
}
