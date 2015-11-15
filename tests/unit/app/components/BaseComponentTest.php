<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/BaseComponent.php';
require_once __DIR__.'/../../../../app/game/GameObject.php';

use \components\BaseComponent;
use \game\GameObject;

class ASimpleComponent extends BaseComponent {
  public $anyValue = "anyValue";
}

class NotAGameObject {

}

class BaseComponentTest extends \PHPUnit_Framework_TestCase
{
  public function testBaseComponent()
  {
    $parent = new GameObject("myObject");
    $this->assertEquals("myObject", $parent->getName());

    $a = new ASimpleComponent();
    $this->assertEquals("anyValue", $a->anyValue);

    $parent->addComponent($a);
    $this->assertTrue($parent->hasComponent("ASimpleComponent"));
    $this->assertEquals($parent->getName(), $a->getParent()->getName());
  }

  /**
   * @expectedException Exception
   */
  public function testException()
  {
    $neverMyParent = new NotAGameObject();
    $a = new ASimpleComponent();
    $a->setParent($neverMyParent);
  }
}
