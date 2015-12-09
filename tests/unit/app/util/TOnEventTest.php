<?php

namespace util\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/util/TOnEvent.php';

use \util\TOnEvent;

class UsesTOnEvent
{
  use TOnEvent;
}

class TOnEventTest extends \PHPUnit_Framework_TestCase
{
  public function testTOnEvent()
  {
    $uses = new UsesTOnEvent();

    $eventHandler = function ($a) {
      return "something interesting like $a";
    };

    $this->assertEquals("something interesting like me", $eventHandler('me'));

    $registeredEvent = $uses->on('particularEvent', $eventHandler);
    $this->assertEquals("something interesting like you", $uses->trigger('particularEvent', array('you')));

    $usesSerial = serialize($uses);
    $usesUnserial = unserialize($usesSerial);

    $this->assertEquals("something interesting like cereal", $usesUnserial->trigger('particularEvent', array('cereal')));

    $poppedHandler = $usesUnserial->popEventHandler('particularEvent');
    $this->assertEquals("something interesting like pop", $poppedHandler('pop'));
    $this->assertNull($usesUnserial->trigger('particularEvent', array('anything')));
  }
}
