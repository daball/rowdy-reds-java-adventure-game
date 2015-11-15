<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Collider.php';

use \components\Collider;

class ColliderTest extends \PHPUnit_Framework_TestCase
{
  public function testCollider()
  {
    $directions = array('north', 'south', 'east', 'west');
    foreach ($directions as $collideDirection) {
      $collider = new Collider($collideDirection);

      $this->assertTrue(is_a($collider, "\components\BaseComponent"));

      //default: collisions enabled
      $this->assertTrue($collider->isEnabled());

      //disable collisions, negative tests
      $collider->disableCollisions();
      $this->assertFalse($collider->isEnabled());
      $this->assertFalse($collider->validateCollision($collideDirection));
      foreach ($directions as $testDirection) {
        $this->assertFalse($collider->validateCollision($testDirection));
        $this->assertFalse(!!$collider->collide($testDirection));
      }

      //enable collisions, positive tests
      $collider->enableCollisions();
      $this->assertTrue($collider->isEnabled());
      $this->assertTrue($collider->validateCollision($collideDirection));
      foreach ($directions as $testDirection) {
        if ($testDirection == $collideDirection) {
          $this->assertTrue($collider->validateCollision($testDirection));
          $this->assertTrue(!!$collider->collide($testDirection));
        }
        else {
          $this->assertFalse($collider->validateCollision($testDirection));
          $this->assertFalse(!!$collider->collide($testDirection));
        }
      }

      $this->assertEquals($collideDirection, $collider->getDirection());
      $collider->setDirection(substr($collideDirection, 0, 1));
      $this->assertEquals($collideDirection, $collider->getDirection());

      //evil collider, collides always
      $collider->disableCollisions();
      $collider->onBeforeCollide(function ($collider, $direction) {
        return true;
      });
      $collider->onCollide(function ($collider, $direction) {
        return "ha ha ha!";
      });
      foreach ($directions as $testDirection) {
        $this->assertTrue(!!$collider->collide($testDirection));
      }

      //dumb collider, collides never
      $collider->enableCollisions();
      $collider->onBeforeCollide(function ($collider, $direction) {
        return false;
      });
      $collider->onCollide(function ($collider, $direction) {
        return "you'll never read this text, ever!";
      });
      foreach ($directions as $testDirection) {
        $this->assertFalse(!!$collider->collide($testDirection));
      }
    }
  }
}
