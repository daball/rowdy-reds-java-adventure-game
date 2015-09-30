<?php

namespace playable;

require_once __DIR__.'/../util/ISerializable.php';
require_once 'GameObject.php';
require_once 'IAssignable.php';
require_once 'IEdidable.php';
require_once 'IInspectable.php';
require_once 'TCreate.php';
require_once 'TAssignable.php';
require_once 'TInspectable.php';
require_once __DIR__.'/../util/ISerializable.php';

/**
 * A LambChop item is used to feed a hungy Dog obstacle.
 */
class LambChop extends GameObject implements IInspectable, IAssignable, IEdidable, \util\ISerializable, \Serializable
{
  use TCreate;
  use TInspectable;
  use TAssignable;

  public function __construct() {
    $this->setDescription("You have found a lambchop.");
  }

  public function serialize() {
    return serialize(
      array(
        'description' => $this->description,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->__construct();
    $this->description = $data['description'];
  }
}
