<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Lockable.php';
require_once __DIR__.'/../../../../app/playable/Key.php';

use \components\Lockable;
use \playable\Key;

class LockableTest extends \PHPUnit_Framework_TestCase
{
  public function testLockable()
  {
    $key = new Key("anyKey", "anySecret");
    $wrongKey = new Key("theWrongKey", "theWrongSecret");
    $lockable = new Lockable($key);

    $this->assertTrue(is_a($lockable, "\components\BaseComponent"));

    $this->assertTrue($lockable->isLocked());
    $this->assertFalse($lockable->isUnlocked());

    $lockable->setUnlocked();
    $this->assertFalse($lockable->isLocked());
    $this->assertTrue($lockable->isUnlocked());

    $lockable->setLocked();
    $this->assertTrue($lockable->isLocked());
    $this->assertFalse($lockable->isUnlocked());

    //negative test: test unlocking with the wrong key
    $this->assertNotEquals($wrongKey->getSecret(), $lockable->getKey()->getSecret());
    $this->assertTrue(!!$lockable->unlock($wrongKey));
    $this->assertTrue($lockable->isLocked());
    $this->assertFalse($lockable->isUnlocked());

    //positive test: test unlocking with the right key
    $this->assertEquals($key->getSecret(), $lockable->getKey()->getSecret());
    $this->assertTrue(!!$lockable->unlock($key));
    $this->assertFalse($lockable->isLocked());
    $this->assertTrue($lockable->isUnlocked());

    //negative test: test locking with the wrong key
    $this->assertNotEquals($wrongKey->getSecret(), $lockable->getKey()->getSecret());
    $this->assertTrue(!!$lockable->lock($wrongKey));
    $this->assertFalse($lockable->isLocked());
    $this->assertTrue($lockable->isUnlocked());

    //positive test: test locking with the right key
    $this->assertEquals($key->getSecret(), $lockable->getKey()->getSecret());
    $this->assertTrue(!!$lockable->lock($key));
    $this->assertTrue($lockable->isLocked());
    $this->assertFalse($lockable->isUnlocked());
  }
}
