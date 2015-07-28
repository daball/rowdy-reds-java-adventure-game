<?php

require_once __DIR__.'/../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../app/SampleMap.php';

///Unit tests SampleMap class
class SampleMapTest extends PHPUnit_Framework_TestCase
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
    $this->assertEquals('hall', $map->getRoom('entrance')->directions['n']->jumpTo);
    $this->assertEquals('kitchen', $map->getRoom('hall')->directions['w']->jumpTo);

    //test reverse direction
    $this->assertEquals('entrance', $map->getRoom('hall')->directions['s']->jumpTo);
    $this->assertEquals('hall', $map->getRoom('kitchen')->directions['e']->jumpTo);

    //test spawn point
    $this->assertEquals('entrance', $map->getSpawnPoint());
  }
}
