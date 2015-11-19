<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Openable.php';
require_once __DIR__.'/../../../../app/game/GameObject.php';
require_once __DIR__.'/../../../../app/playable/Key.php';

use \components\Openable;
use \game\GameObject;
use \playable\Key;

class OpenableTest extends \PHPUnit_Framework_TestCase
{
  public function testOpenable()
  {
    $openable = new Openable();

    $this->assertTrue(is_a($openable, "\components\BaseComponent"));

    $this->assertFalse($openable->isOpened());
    $this->assertTrue($openable->isClosed());

    $openable->setOpened();
    $this->assertTrue($openable->isOpened());
    $this->assertFalse($openable->isClosed());

    $openable->setClosed();
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
    $openable->setOpened();
    $openable->onBeforeClose(function ($openable) {
      return false;
    });
    $this->assertTrue(!!$openable->close());
    $this->assertFalse($openable->isClosed());

    //test a openable that never opens
    $openable->setClosed();
    $openable->onBeforeOpen(function ($openable) {
      return false;
    });
    $this->assertTrue(!!$openable->close());
    $this->assertFalse($openable->isOpened());
  }
}
