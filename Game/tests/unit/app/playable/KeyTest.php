<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/playable/Key.php';

use \playable\Key;

class KeyTest extends \PHPUnit_Framework_TestCase
{
  public function testKey()
  {
    $key = new Key('anyKey', 'anySecret');
    $this->assertTrue(is_a($key, "\game\GameObject"));
    $this->assertEquals('anyKey', $key->getName());
    $this->assertEquals('anySecret', $key->getSecret());

    $serialized = serialize($key);
    $unserialized = unserialize($serialized);

    $this->assertEquals($key->getName(), $unserialized->getName());
    $this->assertEquals($key->getSecret(), $unserialized->getSecret());
  }
}
