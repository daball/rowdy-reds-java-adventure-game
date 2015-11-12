<?php
namespace playable;

require_once 'GameObject.php';
require_once 'Key.php';
require_once __DIR__.'/../components/Closeable.php';
require_once __DIR__.'/../components/Collider.php';
require_once __DIR__.'/../components/Inspector.php';
require_once __DIR__.'/../components/Lockable.php';
require_once __DIR__.'/../components/Openable.php';
require_once __DIR__.'/../components/Unlockable.php';

use \components\Closeable;
use \components\Collider;
use \components\Inspector;
use \components\Lockable;
use \components\Openable;
use \components\Unlockable;

class Door extends GameObject {
  public function __construct($direction) {
    $collider = new Collider($direction);
    $inspector = new Inspector();
    $openable = new Openable();
    $closeable = new Closeable($openable);
    $collider->onCollide(function ($collider) {
      $direction = $collider->getDirection();
      return "There is a door to your $direction. You might try to open it first.";
    });
    $openable->onOpen(function ($openable) {
      $door = $openable->getParent();
      $collider = $door->getComponent('Collider');
      if ($openable->isOpened()) {
        $collider->disableCollisions();
        return "The door is now open. You may now pass through.";
      }
      else {
        return "You tried to open the door, but it will not budge.";
      }
    });
    $inspector->onBeforeInspect(function ($inspector) {
      $door = $inspector->getParent();
      $collider = $door->getComponent('Collider');
      $openable = $door->getComponent('Openable');
      $direction = $collider->getDirection();
      if ($openable->isOpened())
        return "There is a open door to your $direction.";
      else
        return "There is a door blocking your path to the $direction.";
    });
    $this->addComponent($collider);
    $this->addComponent($inspector);
    $this->addComponent($openable);
    $this->addComponent($closeable);
  }
}

class LockedDoor extends Door {
  public function __construct($direction, $key) {
    parent::__construct($direction);

    //add new components
    $lockable = new Lockable($key);
    $unlockable = new Unlockable($lockable);
    $this->addComponent($lockable);
    $this->addComponent($unlockable);

    //override Door
    $this->getComponent('Inspector')->onBeforeInspect(function ($inspector) {
      $door = $inspector->getParent();
      $lockable = $door->getComponent('Lockable');
      $openable = $door->getComponent('Openable');
      $collider = $door->getComponent('Collider');
      $direction = $collider->getDirection();
      if ($lockable->isLocked()) {
        return "There is a locked door to your $direction.";
      }
      else {
        if ($openable->isOpened())
          return "There is an unlocked door to your $direction.";
        else
          return "There is an unlocked door to your $direction, but it is closed.";
      }
    });
  }
}


$door = new Door('north');
echo "> north\n";
echo $door->getComponent('Collider')->collide() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> door.open();\n";
echo $door->getComponent('Openable')->open() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> north\n";
$collision = $door->getComponent('Collider')->collide();
echo $collision?"You did not pass through.":"You passed through." . "\n";
echo "> door.close();\n";
echo $door->getComponent('Closeable')->close() . "\n";


$key = new Key('funkyKey');
$door = new LockedDoor('south', $key);
echo "> south\n";
echo $door->getComponent('Collider')->collide() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> door.open();\n";
echo $door->getComponent('Openable')->open() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> door.unlock(leftHand);\n";
echo $door->getComponent('Unlockable')->unlock($key) . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> south\n";
$collision = $door->getComponent('Collider')->collide();
echo ($collision?"You did not pass through.":"You passed through.") . "\n";
echo "> door.close();\n";
echo $door->getComponent('Closeable')->close() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> door.open();\n";
echo $door->getComponent('Openable')->open() . "\n";
echo "> door.lock(leftHand);\n";
echo $door->getComponent('Lockable')->lock($key) . "\n";
