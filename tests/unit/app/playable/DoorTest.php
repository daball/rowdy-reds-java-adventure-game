<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/playable/Door.php';

use \playable\Door;

class DoorTest extends \PHPUnit_Framework_TestCase
{
  public function testDoor()
  {
    $directions = array('north', 'south', 'east', 'west');

    foreach ($directions as $direction) {
      $doorName = $direction."Door";
      $door = new Door($doorName, $direction);
      $this->assertTrue(is_a($door, '\game\GameObject'));
      $this->assertEquals($doorName, $door->getName());

      $this->assertTrue($door->hasComponent('Collider'));
      $this->assertTrue($door->hasComponent('Inspector'));
      $this->assertTrue($door->hasComponent('Openable'));

      $collider = $door->getComponent('Collider');
      $inspector = $door->getComponent('Inspector');
      $openable = $door->getComponent('Openable');

      //test initial state
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

      //test open door
      $this->assertTrue(!!$openable->open());
      $this->assertFalse($openable->isClosed());
      $this->assertTrue($openable->isOpened());
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
