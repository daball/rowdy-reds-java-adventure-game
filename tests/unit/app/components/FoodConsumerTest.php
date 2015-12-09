<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/FoodConsumer.php';
require_once __DIR__.'/../../../../app/playable/Food.php';
require_once __DIR__.'/../../../../app/game/GameObject.php';

use \components\FoodConsumer;
use \playable\Food;
use \game\GameObject;

class FoodConsumerTest extends \PHPUnit_Framework_TestCase
{
  public function testFoodConsumer()
  {
    $food = new Food('anyFood');
    $foodConsumer = new FoodConsumer('anyFoodEater');
    $nonFood = new GameObject('nonFood');

    $this->assertTrue($foodConsumer->isHungry());
    $this->assertTrue(!!$foodConsumer->eat($nonFood));
    $this->assertTrue($foodConsumer->isHungry());
    $this->assertTrue(!!$foodConsumer->eat($food));
    $this->assertFalse($foodConsumer->isHungry());
  }
}
