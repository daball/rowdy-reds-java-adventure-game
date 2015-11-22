<?php

namespace game\tests;

require_once __DIR__.'/../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';

use \engine\GameEngine;
use \game\MapBuilder;

abstract class MockPlayTestCase extends \PHPUnit_Framework_TestCase
{
  public function playCommand($gameEngine, $commandInput, $expectedOutput)
  {

  }
}
