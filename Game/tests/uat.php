<?php

require_once '../vendor/phpunit/phpunit-selenium/PHPUnit/Extensions/SeleniumTestCase.php';

class UserAcceptanceTests extends PHPUnit_Extensions_Selenium2TestCase
{
  public static $browsers = array(
      array(
        'name'    => 'Firefox',
        'browser' => '*firefox',
        // 'host'    => 'my.linux.box',
        // 'port'    => 4444,
        'timeout' => 30000,
      )
    , array(
        'name'    => 'Chrome',
        'browser' => '*chrome',
        // 'host'    => 'my.macosx.box',
        // 'port'    => 4444,
        'timeout' => 30000,
      )
    // , array(
    //     'name'    => 'Safari',
    //     'browser' => '*safari',
    //     // 'host'    => 'my.macosx.box',
    //     // 'port'    => 4444,
    //     'timeout' => 30000,
    //   )
    // , array(
    //     'name'    => 'Safari',
    //     'browser' => '*custom C:\Program Files\Safari\Safari.exe -url',
    //     // 'host'    => 'my.windowsxp.box',
    //     // 'port'    => 4444,
    //     'timeout' => 30000,
    //   )
    // , array(
    //     'name'    => 'Internet Explorer',
    //     'browser' => '*iexplore',
    //     // 'host'    => 'my.windowsxp.box',
    //     // 'port'    => 4444,
    //     'timeout' => 30000,
    //   )
  );

  protected function setUp()
  {
      $this->setBrowserUrl('http://localhost/Game');
  }

  public function testTitle()
  {
  }

}
?>
