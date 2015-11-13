<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Openable.php';
require_once __DIR__.'/../../../../app/components/Lockable.php';
require_once __DIR__.'/../../../../app/playable/GameObject.php';
require_once __DIR__.'/../../../../app/playable/Key.php';

use \components\Openable;
use \components\Lockable;
use \playable\GameObject;
use \playable\Key;

class OpenableTest extends \PHPUnit_Framework_TestCase
{
  public function testOpenable()
  {
    $openable = new Openable();

    $this->assertTrue(is_a($openable, "\components\BaseComponent"));

    $this->assertFalse($openable->isOpened());
    $this->assertTrue($openable->isClosed());

    $openable->setOpen();
    $this->assertTrue($openable->isOpened());
    $this->assertFalse($openable->isClosed());

    $openable->setClose();
    $this->assertFalse($openable->isOpened());
    $this->assertTrue($openable->isClosed());

    //positive test: test opening a closed object
    $this->assertTrue(!!$openable->open());
    $this->assertTrue($openable->isOpened());
    $this->assertFalse($openable->isClosed());

    //negative test: test opening an already open thing
    $this->assertTrue(!!$openable->open());
    $this->assertTrue($openable->isOpened());
    $this->assertFalse($openable->isClosed());

    //positive test: test closing an open object
    $this->assertTrue(!!$openable->close());
    $this->assertFalse($openable->isOpened());
    $this->assertTrue($openable->isClosed());

    //negative test: test closing an already closed thing
    $this->assertTrue(!!$openable->close());
    $this->assertFalse($openable->isOpened());
    $this->assertTrue($openable->isClosed());

    //test a openable that never closes
    $openable->setOpen();
    $openable->onBeforeClose(function ($openable) {
      return false;
    });
    $this->assertTrue(!!$openable->close());
    $this->assertFalse($openable->isClosed());

    //test a openable that never opens
    $openable->setClose();
    $openable->onBeforeOpen(function ($openable) {
      return false;
    });
    $this->assertTrue(!!$openable->close());
    $this->assertFalse($openable->isOpened());
  }

  public function testOpenableIntegrationLockable()
  {
    $openable = new Openable();
    $this->assertTrue($openable->isClosed());

    $key = new Key("anyKey");
    $lockable = new Lockable($key);
    $this->assertTrue($lockable->isLocked());

    $parent = new GameObject("myGameObject");
    $parent->addComponent($openable);
    //TESTER: do not add lockable yet, we need to negative test
    //branch two in openable->onBeforeOpen

    $this->assertTrue(!!$openable->open());
    $this->assertTrue($openable->isOpened());
    $this->assertTrue(!!$openable->close());

    //now add the lockable, to test branch three in openable->onBeforeOpen
    $parent->addComponent($lockable);

    $this->assertTrue(!!$openable->open());
    $this->assertFalse($openable->isOpened());
    $this->assertTrue(!!$openable->close());

    $this->assertTrue(!!$lockable->unlock($key));
    $this->assertTrue(!!$openable->open());
    $this->assertTrue($openable->isOpened());
    $this->assertTrue(!!$openable->close());
  }
}
