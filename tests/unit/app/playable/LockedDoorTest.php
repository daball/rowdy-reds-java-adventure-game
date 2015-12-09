<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/playable/LockedDoor.php';
require_once __DIR__.'/../../../../app/playable/Key.php';

use \playable\LockedDoor;
use \playable\Key;

class LockedDoorTest extends \PHPUnit_Framework_TestCase
{
  public function testLockedDoor()
  {
    $directions = array('north', 'south', 'east', 'west');
    $wrongKey = new Key("wrongKey", "theWrongSecret");

    foreach ($directions as $direction) {
      $key = new Key("key_$direction", "secret_$direction");
      $doorName = $direction . "LockedDoor";
      $door = new LockedDoor($doorName, $direction, $key->getSecret());
      $this->assertTrue(is_a($door, '\game\GameObject'));
      $this->assertEquals($doorName, $door->getName());

      $this->assertTrue($door->hasComponent('Collider'));
      $this->assertTrue($door->hasComponent('Inspector'));
      $this->assertTrue($door->hasComponent('Openable'));

      $collider = $door->getComponent('Collider');
      $inspector = $door->getComponent('Inspector');
      $openable = $door->getComponent('Openable');
      $lockable = $door->getComponent('Lockable');

      //test initial state
      $this->assertTrue($lockable->isLocked());
      $this->assertFalse($lockable->isUnlocked());
      $this->assertTrue($openable->isClosed());
      $this->assertFalse($openable->isOpened());

      //test inspect
      $this->assertTrue(!!$inspector->inspect());
      //test collide
      foreach ($directions as $testDirection) {
        if ($direction == $testDirection)
          $this->assertTrue(!!$collider->collide($testDirection));
        else
          $this->assertFalse(!!$collider->collide($testDirection));
      }

      //test unlock door
      $this->assertTrue(!!$lockable->unlock($key));
      $this->assertFalse($openable->isClosed());
      $this->assertFalse($lockable->isLocked());
      $this->assertTrue($openable->isOpened());
      $this->assertTrue($lockable->isUnlocked());
      $this->assertTrue(!!$inspector->inspect());

      //test inspect
      $this->assertTrue(!!$inspector->inspect());
      //negative test collide
      foreach ($directions as $testDirection) {
        $this->assertFalse(!!$collider->collide($testDirection));
      }

      //test close door
      $this->assertTrue(!!$openable->close());
      $this->assertTrue($openable->isClosed());
      $this->assertFalse($openable->isOpened());
      $this->assertTrue($lockable->isUnlocked());
      $this->assertFalse($lockable->isLocked());
      $this->assertTrue(!!$inspector->inspect());

      //test open door
      $this->assertTrue(!!$openable->open());
      $this->assertTrue($openable->isOpened());
      $this->assertFalse($openable->isClosed());
      $this->assertTrue($lockable->isUnlocked());
      $this->assertFalse($lockable->isLocked());
      $this->assertTrue(!!$inspector->inspect());

      //test lock door
      $this->assertTrue(!!$lockable->lock($key));
      $this->assertFalse($openable->isOpened());
      $this->assertTrue($openable->isClosed());
      $this->assertFalse($lockable->isUnlocked());
      $this->assertTrue($lockable->isLocked());
      $this->assertTrue(!!$inspector->inspect());

      //break the door logic
      $openable->onBeforeOpen(function ($openable) {
        return false;
      });
      $openable->onBeforeClose(function ($openable) {
        return false;
      });

      //test broken close
      $openable->setOpened();
      $this->assertTrue($openable->isOpened());
      $this->assertTrue(!!$openable->close());
      $this->assertTrue($openable->isOpened());

      //test broken open
      $openable->setClosed();
      $this->assertTrue($openable->isClosed());
      $this->assertTrue(!!$openable->open());
      $this->assertTrue($openable->isClosed());
    }
  }
}
