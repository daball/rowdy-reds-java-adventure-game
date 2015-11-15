<?php

namespace util\tests;

require_once __DIR__.'/../../../../app/util/TDefine.php';

use \util\TDefine;

class UsesTDefine
{
  use TDefine;

  private $prop;

  public function getProp() {
    return $this->prop;
  }

  public function setProp($val) {
    $this->prop = $val;
  }
}

class TDefineTest extends \PHPUnit_Framework_TestCase
{
  public function testTDefine()
  {
    $uses = new UsesTDefine();
    $uses->define(function ($uses) {
      $uses->setProp("testValue");
    });
    $this->assertEquals("testValue", $uses->getProp());
  }
}
