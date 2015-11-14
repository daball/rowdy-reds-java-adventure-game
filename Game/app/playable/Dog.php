<?php

namespace playable;

require_once 'GameObject.php';
// require_once 'ICollidable.php';
// require_once 'ICanEat.php';
// require_once 'IInspectable.php';
// require_once 'TCreate.php';
// require_once 'TCollidable.php';
// require_once 'TCanEat.php';
// require_once 'TInspectable.php';


/**
 * A LambChop item is used to feed a hungy Dog obstacle.
 */
class Dog extends GameObject //implements IInspectable, ICollidable, ICanEat, \Serializable
{
  // use TInspectable;
  // use TCollidable;
  // use TCanEat;
  // use TCreate;

  public function __construct()
  {
    parent::__construct();
    $this->onInspect(function () {
      if ($this->hungry) {
        return "You have found a growling dog blocking your path.";
      }
      else {
        return "The dog is now happily eating from his bowl.";
      }
    });
    $this->onEat(function () {
      return "The dog is now happily eating from his bowl.";
    });
  }

  /* ISerializable interface implementation */

  public function serialize() {
    return serialize(
      array(
        'description' => $this->description,
        'hungry' => $this->hungry,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->__construct();
    $this->description = $data['description'];
    $this->hungry = $data['hungry'];
  }
}
