<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/playable/GameObject.php';
require_once __DIR__.'/../../../../app/components/BaseComponent.php';
require_once __DIR__.'/../../../../app/components/Inspector.php';

use \playable\GameObject;
use \components\BaseComponent;
use \components\Inspector;

class GameObjectComponent extends BaseComponent {
  public $anyValue = "anyValue";
}

class NotAComponent {

}

class GameObjectTest extends \PHPUnit_Framework_TestCase
{
  public function testGameObject()
  {
    $gameObject = new GameObject("myObject");
    $this->assertEquals("myObject", $gameObject->getName());
    $gameObject->setName("newName");
    $this->assertEquals("newName", $gameObject->getName());
  }

  public function testBaseComponent()
  {
    $parent = new GameObject("myObject");
    $this->assertEquals("myObject", $parent->getName());

    $component = new GameObjectComponent();
    $this->assertEquals("anyValue", $component->anyValue);

    $parent->addComponent($component);
    $this->assertTrue($parent->hasComponent("GameObjectComponent"));
    $this->assertEquals($parent->getName(), $component->getParent()->getName());

    $parent->removeComponent("GameObjectComponent");
    $this->assertFalse($parent->hasComponent("GameObjectComponent"));

    $this->assertNull($parent->getComponent("NoSuchComponent"));

    $parent->addComponent($component);
    $this->assertTrue($parent->hasComponent("GameObjectComponent"));
    $this->assertEquals($parent->getName(), $component->getParent()->getName());

    $serialized = serialize($parent);
    echo $serialized;
    $unserialized = unserialize($serialized);

    $this->assertEquals($parent->getName(), $unserialized->getName());
    $this->assertTrue($unserialized->hasComponent("GameObjectComponent"));
    $this->assertEquals($component->anyValue, $unserialized->getComponent("GameObjectComponent")->anyValue);
    $this->assertEquals($parent->getName(), $unserialized->getComponent("GameObjectComponent")->getParent()->getName());
  }

  /**
   * @expectedException Exception
   **/
  public function testInvalidComponent()
  {
    $parent = new GameObject("myObject");
    $invalidComponent = new NotAComponent();
    $parent->addComponent($invalidComponent);
  }

  public function testDefine()
  {
    $dog = (new GameObject("dog"))->define(function ($dog) {
      $inspector = new Inspector();
      $inspector->onInspect(function ($inspector) {
        return "I am a dog.";
      });
      $dog->addComponent($inspector);
    });
    $this->assertEquals("I am a dog.", $dog->getComponent("Inspector")->inspect());
  }
}
