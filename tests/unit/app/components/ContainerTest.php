<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Assignable.php';
require_once __DIR__.'/../../../../app/components/Container.php';
require_once __DIR__.'/../../../../app/game/GameObject.php';

use \components\Assignable;
use \components\Container;
use \game\GameObject;

class BasicItem extends GameObject
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->addComponent(new Assignable());
  }
}

class NotAContainableItem { }

class ContainerTest extends \PHPUnit_Framework_TestCase
{
  public function testContainer()
  {
    $container = new Container("myContainer");
    $this->assertTrue(is_a($container, "\Components\BaseComponent"));

    for ($i = 0; $i < 10; $i++) {
      $item = new BasicItem("item$i");
      $this->assertTrue($container->isItemAValidType($item));
      $container->insertItem($item);
      $this->assertEquals("item" . $i, $container->getItemAt($i)->getName());
      $this->assertEquals($i, $container->findItemIndexByName("item$i"));
      $this->assertEquals("item" . $i, $container->findItemByName("item$i")->getName());
      $this->assertTrue($container->itemExists($item));
      $this->assertNotNull($container->findNestedItemByName("item$i"));
    }
    foreach ($container->getAllItems() as $i => $item) {
      $this->assertEquals("item" . $i, $item->getName());
      $this->assertTrue($container->itemExists($item));
    }
    $this->assertEquals(10, $container->countItems());
    $this->assertEquals(10, count($container->getAllItems()));
    $this->assertNull($container->getItemAt(11));
    $this->assertNull($container->getItemAt(-1));
    $this->assertLessThan(0, $container->findItemIndexByName("item100"));
    $this->assertNull($container->findItemByName("item100"));

    $invalidItem = new NotAContainableItem();
    $this->assertFalse($container->isItemAValidType($invalidItem));
    $this->assertFalse($container->isItemAssignable($invalidItem));
    $this->assertTrue(!!$container->setItemAt(0, $invalidItem));
    $this->assertFalse($container->itemExists($invalidItem));

    $this->assertLessThan(0, $container->getMaxItems());
    $container->setMaxItems(10);
    $this->assertEquals(10, $container->getMaxItems());
    $container->setItemAt(10, new BasicItem("item10"));
    $this->assertNull($container->getItemAt(10));

    for ($i = 0; $i < 5; $i++) {
      $container->unsetItemAt($i);
      $this->assertNull($container->getItemAt($i));
    }
    for ($i = 5; $i < 10; $i++) {
      $this->assertNotNull($container->getItemAt($i));
    }

    $this->assertTrue(!!$container->unsetItemAt(11));
    $this->assertTrue(!!$container->unsetItemAt(0));

    //try a dumb container
    $container->onBeforeSet(function ($container, $index, $item) {
      return false;
    });
    $container->onBeforeUnset(function ($container, $index, $item) {
      return false;
    });
    $this->assertTrue(!!$container->unsetItemAt(7));
    $this->assertTrue(!!$container->unsetItemAt(6));
    $this->assertTrue(!!$container->setItemAt(1, new BasicItem("item1")));
  }
}
