<?php

namespace util\tests;

require_once __DIR__.'/../../../../app/util/TSerializableClosure.php';

use \util\TSerializableClosure;

class UsesTSerializableClosure
{
  use TSerializableClosure;

  public function getSerializable($fn) {
    return $this->serializableClosure($fn);
  }
}

class TSerializableClosureTest extends \PHPUnit_Framework_TestCase
{
  public function testTSerializableClosure()
  {
    $uses = new UsesTSerializableClosure();
    $thisClosure = function () {
      return "this";
    };
    $thatClosure = function () {
      return "that";
    };
    $this->assertEquals("this", $thisClosure());
    $this->assertEquals("that", $thatClosure());

    $thisClosure = $uses->getSerializable($thisClosure);
    $thatClosure = $uses->getSerializable($thatClosure);
    $this->assertEquals("this", $thisClosure());
    $this->assertEquals("that", $thatClosure());

    $thisSerial = serialize($thisClosure);
    $thatSerial = serialize($thatClosure);

    $thisThing = unserialize($thisSerial);
    $thatThing = unserialize($thatSerial);
    $this->assertEquals("this", $thisThing());
    $this->assertEquals("that", $thatThing());
  }
}
