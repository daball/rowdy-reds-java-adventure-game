<?php

require_once 'UserAcceptanceTest.php';
require_once __DIR__.'/../../vendor/phpunit/phpunit-selenium/PHPUnit/Extensions/SeleniumTestCase.php';
require_once __DIR__.'/../../app/GameEngine.php';

//UAT tests index0.php
class UserAcceptance0Test extends UserAcceptanceTest
{
  public function __construct()
  {
    parent::__construct();
    $this->commandHistoryLocator = "id=commandHistory";
    $this->url = "http://localhost/Game/index0.php";
  }
}
