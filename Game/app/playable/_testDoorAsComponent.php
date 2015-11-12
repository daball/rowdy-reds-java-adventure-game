<?php
namespace playable;

require_once 'GameObject.php';
require_once __DIR__.'/../components/Inspector.php';
require_once __DIR__.'/../components/Lockable.php';

use \components\Lockable;
use \components\Inspector;

class Door extends GameObject {
  public function __construct() {
    $doorLockable = new Lockable();
    $doorInspector = new Inspector();
    $doorInspector->onBeforeInspect(function ($inspector) {
      $door = $inspector->getParent();
      $lockable = $door->getComponent('Lockable');
      if ($lockable->isLocked()) {
        return "The door is locked.";
      }
      else {
        return "The door is unlocked.";
      }
    });
    $this->addComponent($doorInspector);
    $this->addComponent($doorLockable);
  }
}

$door = new Door();

echo $door->getComponent('Inspector')->inspect() . "\n";
$door->getComponent('Lockable')->unlock();
echo $door->getComponent('Inspector')->inspect() . "\n";
