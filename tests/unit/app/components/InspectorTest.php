<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Inspector.php';

use \components\Inspector;

class InspectorTest extends \PHPUnit_Framework_TestCase
{
  public function testInspector()
  {
    $inspector = new Inspector();
    $this->assertTrue(is_a($inspector, "\components\BaseComponent"));

    $this->assertTrue(!!$inspector->inspect());

    $inspector->onInspect(function ($inspector) {
      return "My Inspection";
    });

    $this->assertEquals("My Inspection", $inspector->inspect());
    $this->assertTrue(!!$inspector->inspect());
  }
}
