<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Lockable.php';
require_once __DIR__.'/../../../../app/components/Openable.php';
require_once __DIR__.'/../../../../app/game/GameObject.php';
require_once __DIR__.'/../../../../app/playable/Key.php';

use \components\Lockable;
use \components\Openable;
use \game\GameObject;
use \playable\Key;

class LockableTest extends \PHPUnit_Framework_TestCase
{
  public function testLockable()
  {
    $key = new Key("anyKey", "anySecret");
    $wrongKey = new Key("theWrongKey", "theWrongSecret");
    $lockable = new Lockable($key->getSecret());

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
    $this->assertNotEquals($wrongKey->getSecret(), $lockable->getSecret());
    $this->assertTrue(!!$lockable->unlock($wrongKey));
    $this->assertTrue($lockable->isLocked());
    $this->assertFalse($lockable->isUnlocked());

    //positive test: test unlocking with the right key
    $this->assertEquals($key->getSecret(), $lockable->getSecret());
    $this->assertTrue(!!$lockable->unlock($key));
    $this->assertFalse($lockable->isLocked());
    $this->assertTrue($lockable->isUnlocked());

    //negative test: test locking with the wrong key
    $this->assertNotEquals($wrongKey->getSecret(), $lockable->getSecret());
    $this->assertTrue(!!$lockable->lock($wrongKey));
    $this->assertFalse($lockable->isLocked());
    $this->assertTrue($lockable->isUnlocked());

    //positive test: test locking with the right key
    $this->assertEquals($key->getSecret(), $lockable->getSecret());
    $this->assertTrue(!!$lockable->lock($key));
    $this->assertTrue($lockable->isLocked());
    $this->assertFalse($lockable->isUnlocked());
  }

  public function testLockableOpenableIntegration()
  {
    $openable = new Openable();
    $this->assertTrue($openable->isClosed());

    $key = new Key("anyKey", "anySecret");
    $lockable = new Lockable($key->getSecret());
    $this->assertTrue($lockable->isLocked());

    $parent = new GameObject("myGameObject");
    $parent->addComponent($openable);

    $this->assertTrue(!!$openable->open());
    $this->assertTrue($openable->isOpened());
    $this->assertTrue(!!$openable->close());
    $this->assertTrue($openable->isClosed());

    //now add the lockable, to test branch three in openable->onBeforeOpen
    $parent->addComponent($lockable);

    $this->assertTrue(!!$openable->open());
    $this->assertTrue($openable->isClosed());
    $this->assertTrue(!!$openable->close());

    $this->assertTrue(!!$lockable->unlock($key));
    $this->assertTrue(!!$openable->open());
    $this->assertTrue($openable->isOpened());
    $this->assertTrue(!!$openable->close());
    $this->assertTrue($openable->isClosed());
    $this->assertTrue(!!$openable->open());
    $this->assertTrue($openable->isOpened());
    $this->assertTrue(!!$lockable->lock($key));
    $this->assertTrue($lockable->isLocked());
    $this->assertTrue($openable->isClosed());
  }
}
