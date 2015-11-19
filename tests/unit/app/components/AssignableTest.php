<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Assignable.php';
require_once __DIR__.'/../../../../app/playable/BasicContainer.php';
require_once __DIR__.'/../../../../app/game/GameObject.php';

use \components\Assignable;
use \playable\BasicContainer;
use \game\GameObject;

class AssignableItem extends GameObject
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->addComponent(new Assignable());
  }
}

class AssignableTest extends \PHPUnit_Framework_TestCase
{
  public function testContainer()
  {
    $basicContainer = new BasicContainer("myContainer");
    $this->assertTrue($basicContainer->hasComponent('Container'));
    $container = $basicContainer->getComponent('Container');
    $item = new AssignableItem("myItem");
    $this->assertTrue($item->hasComponent('Assignable'));
    $this->assertTrue($container->isItemAValidType($item));
    $container->insertItem($item);
    $this->assertEquals($basicContainer->getName(), $item->getContainer()->getName());
    $this->assertEquals(1, $basicContainer->getComponent('Container')->countItems());
    $this->assertEquals(0, $basicContainer->getComponent('Container')->findItemIndexByName($item->getName()));
    $this->assertNotNull($basicContainer->getComponent('Container')->findItemByName($item->getName()));
    $this->assertNotNull($basicContainer->getComponent('Container')->findNestedItemByName($item->getName()));

    $basicContainer2 = new BasicContainer("myContainer2");
    $this->assertTrue($basicContainer->hasComponent('Container'));
    $container2 = $basicContainer->getComponent('Container');
    $this->assertTrue($container2->isItemAValidType($item));
    $this->assertEquals(0, $basicContainer2->getComponent('Container')->countItems());

    //perform assignment
    $basicContainer->getComponent('Container')->findNestedItemByName('myItem')->getComponent('Assignable')->assignTo($basicContainer2);
    $this->assertEquals(1, $basicContainer2->getComponent('Container')->countItems());
    $this->assertEquals(0, $basicContainer->getComponent('Container')->countItems());
    $this->assertEquals(0, $basicContainer2->getComponent('Container')->findItemIndexByName($item->getName()));
    $this->assertNotNull($basicContainer2->getComponent('Container')->findItemByName($item->getName()));
    $this->assertNotNull($basicContainer2->getComponent('Container')->findNestedItemByName($item->getName()));
  }
}
