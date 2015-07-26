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

  public function testNavigateNorth()
  {
    //User Story: User Navigation
    //Given:
    //      Game running on any map,
    //      Player is in any room,
    //      A room exists in the northern direction
    //Trigger:
    //      Player navigates north by typing north (or a valid command alias)
    //      and pressing the [Enter] key
    //Verification/Then:
    //      Player enters adjacent room to the northern direction.
    //      Game displays the description of the room entered.
  }

  public function testNavigateSouth()
  {
    //User Story: User Navigation
    //Given:
    //      Game running on any map,
    //      Player is in any room,
    //      A room exists in the southern direction
    //Trigger:
    //      Player navigates south by typing south (or a valid command alias)
    //      and pressing the [Enter] key
    //Verification/Then:
    //      Player enters adjacent room to the southern direction.
    //      Game displays the description of the room entered.
  }

  public function testNavigateWest()
  {
    //User Story: User Navigation
    //Given:
    //      Game running on any map,
    //      Player is in any room,
    //      A room exists in the western direction
    //Trigger:
    //      Player navigates west by typing west (or a valid command alias)
    //      and pressing the [Enter] key
    //Verification/Then:
    //      Player enters adjacent room to the western direction.
    //      Game displays the description of the room entered.
  }

  public function testNavigateEast()
  {
    //User Story: User Navigation
    //Given:
    //      Game running on any map,
    //      Player is in any room,
    //      A room exists in the eastern direction
    //Trigger:
    //      Player navigates east by typing east (or a valid command alias)
    //      and pressing the [Enter] key
    //Verification/Then:
    //      Player enters adjacent room to the eastern direction.
    //      Game displays the description of the room entered.
  }

  public function testInvalidNavigateNorth()
  {
    //User Story: Invalid User Navigation
    //Given:
    //      Game running on any map,
    //      Player is in any room,
    //      A room does NOT exist in the northern direction
    //Trigger:
    //      Player navigates north by typing north (or a valid command alias)
    //      and pressing the [Enter] key
    //Verification/Then:
    //      Game will display a message or an error such as "You cannot go that way"
  }

  public function testInvalidNavigateSouth()
  {
    //User Story: Invalid User Navigation
    //Given:
    //      Game running on any map,
    //      Player is in any room,
    //      A room does NOT exist in the southern direction
    //Trigger:
    //      Player navigates south by typing south (or a valid command alias)
    //      and pressing the [Enter] key
    //Verification/Then:
    //      Game will display a message or an error such as "You cannot go that way"
  }

  public function testInvalidNavigateWest()
  {
    //User Story: Invalid User Navigation
    //Given:
    //      Game running on any map,
    //      Player is in any room,
    //      A room does NOT exist in the western direction
    //Trigger:
    //      Player navigates west by typing west (or a valid command alias)
    //      and pressing the [Enter] key
    //Verification/Then:
    //      Game will display a message or an error such as "You cannot go that way"
  }

  public function testInvalidNavigateEast()
  {
    //User Story: Invalid User Navigation
    //Given:
    //      Game running on any map,
    //      Player is in any room,
    //      A room does NOT exist in the eastern direction
    //Trigger:
    //      Player navigates east by typing east (or a valid command alias)
    //      and pressing the [Enter] key
    //Verification/Then:
    //      Game will display a message or an error such as "You cannot go that way"
  }

  public function testExit()
  {
//   describe("Exit Verb User Story", function () {
//     describe("Player is anywhere in the game.", function () {
//       it("Player exits game by typing exit and pressing the [Enter] key.", function () {
//         //Verification/Then: Game exits.
//       });
//     });
//   });
  }

  public function testRestart()
  {
//   describe("Restart Verb User Story", function () {
//     describe("Player is anywhere in the game (except for the initial state).", function () {
//       it("Player resets the game by typing reset and pressing the [Enter] key.", function () {
//         //Verification/Then: Game resets back to the initial game state.
//       });
//     });
//   });
  }

  public function testUnknownCommand()
  {
//   describe("Unknown Command User Story", function () {

//     describe("Player is anywhere in the game.", function () {
//       it("Player types no command and presses the [Enter] key.", function () {
//         //Verification/Then: Game ignores input, awaits further input from Player.
//       });
//       it("Player types something that is NOT a command and presses the [Enter] key.", function () {
//         //Verification/Then: Game provides error message such as "I do not understand."
//       });
//     });
//   });
  }

?>
