<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/game/GameObject.php';
require_once __DIR__.'/../../../../app/playable/BasicContainer.php';

use \game\GameObject;
use \playable\BasicContainer;

class BasicContainerTest extends \PHPUnit_Framework_TestCase
{
  public function testBasicContainer()
  {
    $outerBasicContainer = (new BasicContainer("outerBasicContainer"))->define(function ($outerBasicContainer) {
      $innerBasicContainer = (new BasicContainer("innerBasicContainer"))->define(function ($innerBasicContainer) {
        $innerBasicContainer->getComponent('Container')->insertItem(new GameObject("embeddedItem"));
      });
      $outerBasicContainer->getComponent('Container')->insertItem($innerBasicContainer);
    });

    $this->assertTrue(is_a($outerBasicContainer, '\game\GameObject'));
    $this->assertEquals("outerBasicContainer", $outerBasicContainer->getName());
    $this->assertTrue($outerBasicContainer->hasComponent('Container'));
    $outerContainer = $outerBasicContainer->getComponent('Container');
    $this->assertEquals(1, $outerContainer->countItems());

    $innerBasicContainer = $outerContainer->findItemByName("innerBasicContainer");
    $this->assertTrue(is_a($innerBasicContainer, '\game\GameObject'));
    $this->assertEquals("innerBasicContainer", $innerBasicContainer->getName());
    $this->assertTrue($innerBasicContainer->hasComponent('Container'));
    $innerContainer = $innerBasicContainer->getComponent('Container');
    $this->assertEquals(1, $innerContainer->countItems());

    $embeddedItem = $innerContainer->findItemByName("embeddedItem");
    $this->assertTrue(is_a($embeddedItem, '\game\GameObject'));
    $this->assertEquals("embeddedItem", $embeddedItem->getName());
    $embeddedItem = null;

    $embeddedItem = $outerContainer->findNestedItemByName("embeddedItem");
    $this->assertTrue(is_a($embeddedItem, '\game\GameObject'));
    $this->assertEquals("embeddedItem", $embeddedItem->getName());
  }
}
