<?php

namespace game\tests;
use \game\SampleMap;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/game/SampleMap.php';

///Unit tests SampleMap class
class SampleMapTest extends \PHPUnit_Framework_TestCase
{
  public function testSampleMap()
  {
    //build sample map
    $map = SampleMap::buildSampleMap();

    //check for known rooms
    $this->assertEquals('entrance', $map->getRoom('entrance')->name);
    $this->assertEquals('hall', $map->getRoom('hall')->name);
    $this->assertEquals('kitchen', $map->getRoom('kitchen')->name);

    //test forward direction
    $this->assertEquals('hall', $map->getRoom('entrance')->directions['n']->nextRoom);
    $this->assertEquals('kitchen', $map->getRoom('hall')->directions['w']->nextRoom);

    //test reverse direction
    $this->assertEquals('entrance', $map->getRoom('hall')->directions['s']->nextRoom);
    $this->assertEquals('hall', $map->getRoom('kitchen')->directions['e']->nextRoom);

    //test spawn point
    $this->assertEquals('entrance', $map->getSpawnPoint());
  }
}
