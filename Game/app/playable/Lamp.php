<?php

namespace playable;

require_once 'GameObject.php';
// require_once 'ICollidable.php';
// require_once 'ICanEat.php';
// require_once 'IAssignable.php';
// require_once 'IInspectable.php';
// require_once 'TCreate.php';
// require_once 'TCollidable.php';
// require_once 'TCanEat.php';
// require_once 'TInspectable.php';
// require_once 'TAssignable.php';


/**
 * A Lamp item is used to light dark areas.
 */
class Lamp extends GameObject //implements IInspectable, IAssignable, \Serializable
{
  // use TInspectable;
  // use TCreate;
  // use TAssignable;

  public function __construct()
  {
    parent::__construct();
    $this->setDescription("You found a lamp.");
  }

  /* ISerializable interface implementation */

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
