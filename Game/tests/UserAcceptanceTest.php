<?php

require_once '../vendor/phpunit/phpunit-selenium/PHPUnit/Extensions/SeleniumTestCase.php';
require_once __DIR__.'/../app/GameEngine.php';

class UserAcceptanceTest extends PHPUnit_Extensions_SeleniumTestCase
{
  // public static $browsers = array(
  //     array(
  //       'name'    => 'Firefox',
  //       'browser' => '*firefox',
  //       'host'    => 'localhost',
  //       'port'    => 4444,
  //       'timeout' => 30000,
  //     )
  //   , array(
  //       'name'    => 'Chrome',
  //       'browser' => '*googlechrome',
  //       'host'    => 'localhost',
  //       'port'    => 4444,
  //       'timeout' => 30000,
  //     )
  //   // , array(
  //   //     'name'    => 'Safari',
  //   //     'browser' => '*safari',
  //   //     // 'host'    => 'my.macosx.box',
  //   //     // 'port'    => 4444,
  //   //     'timeout' => 30000,
  //   //   )
  //   // , array(
  //   //     'name'    => 'Safari',
  //   //     'browser' => '*custom C:\Program Files\Safari\Safari.exe -url',
  //   //     // 'host'    => 'my.windowsxp.box',
  //   //     // 'port'    => 4444,
  //   //     'timeout' => 30000,
  //   //   )
  //   // , array(
  //   //     'name'    => 'Internet Explorer',
  //   //     'browser' => '*iexplore',
  //   //     // 'host'    => 'my.windowsxp.box',
  //   //     // 'port'    => 4444,
  //   //     'timeout' => 30000,
  //   //   )
  // );

  protected function getBrowserCommandLine()
  {
    //$locator = "id=ans";
    $locator = "id=commandLine";
    $commandLine = trim($this->getValue($locator));
    //echo "getBrowserCommandLine()=>".$commandLine."\n";
    return $commandLine;
  }

  protected function getBrowserCommandLines()
  {
    $eol = "\n";
    $commandLines = explode($eol, $this->getBrowserCommandLine());
    //echo "sizeof(getBrowserCommandLines())=>".sizeof($commandLines)."\n";
    return $commandLines;
  }

  protected function sendBrowserCommand($command)
  {
    $eol = "\n";
    //$locator = "id=ans";
    $locator = "id=commandLine";
    $formLocator = "id=answerForm";
    $existingCommandLinesCount = sizeof($this->getBrowserCommandLines());
    $this->type($locator, $command);
    $this->keyPress($locator, 13);
    $this->submit($formLocator);
    $this->waitForPageToLoad();
    if ($this->isElementPresent($locator))
    {
      $commandLines = $this->getBrowserCommandLines();
      $lastCommandOutputStart = $existingCommandLinesCount-1;
      $lastCommandOutputEnd = sizeof($commandLines)-2;
      if ($lastCommandOutputEnd < $lastCommandOutputStart
        || stristr($command, 'reset')
        || stristr($command, 'restart')) {
        $lastCommandOutputStart = 0;
      }
      $commandOutput = '';
      for ($l = $lastCommandOutputStart; $l <= $lastCommandOutputEnd; $l++)
      {
        $commandOutput .= $commandLines[$l] . $eol;
      }
      //echo "commandoutput=".$commandOutput.$eol;
      return trim($commandOutput);
    }
  }

  public function sendServerCommand($gameEngine, $command)
  {
    $eol = "\n";
    $consoleHistory = $gameEngine->gameState->consoleHistory;
    $commandLines = explode($eol, $consoleHistory);
    //echo "initialconsolehistory=".$consoleHistory;
    $lastCommandOutputStart = sizeof($commandLines);
    $commandOutput = $gameEngine->commandProcessor->dispatchCommand($gameEngine->gameState, $command);
    $gameEngine->gameState->addCommandToHistory($command, $commandOutput);
    $consoleHistory = $gameEngine->gameState->consoleHistory;
    //echo "finalconsolehistory=".$consoleHistory;
    $commandLines = explode($eol, $consoleHistory);
    $lastCommandOutputEnd = sizeof($commandLines);
    if ($lastCommandOutputEnd < $lastCommandOutputStart
      || stristr($command, 'reset')
      || stristr($command, 'restart')) {
      $lastCommandOutputStart = 0;
    }
    $commandOutput = '';
    for ($l = $lastCommandOutputStart; $l < $lastCommandOutputEnd; $l++)
    {
      $commandOutput .= $commandLines[$l] . $eol;
    }
    return trim($commandOutput);
  }

  public function assertBrowserAndServerOutputMatch($gameEngine, $command)
  {
    $serverOutput = $this->sendServerCommand($gameEngine, $command);
    $browserOutput = $this->sendBrowserCommand($command);
    if (!$gameEngine->gameState->isExiting)
      $this->assertEquals($serverOutput, $browserOutput);
  }

  protected function setUp()
  {
    $this->setBrowser("*firefox");
    $this->setBrowserUrl("http://localhost/Game/");
  }

  public function testTitle()
  {
    $this->deleteAllVisibleCookies();
    $this->open("http://localhost/Game/index.php");
    $this->assertEquals("Rowdy Red's Java Adventure", $this->getTitle());
    $this->shareSession(true);
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

    $setupCommands = array(); //do nothing, just go north
    $commandList = array("north", "n", "NORTH", "North", "noRTh", "N", "moveNorth();");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertEquals("hall", $gameEngine->gameState->getAvatarRoom()->name);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $gameEngine->gameState->consoleHistory);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $this->getBrowserCommandLine());
    }
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

    $setupCommands = array("north"); //go north before going south
    $commandList = array("south", "s", "SOUTH", "South", "soUTh", "S", "moveSouth();");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertEquals("entrance", $gameEngine->gameState->getAvatarRoom()->name);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $gameEngine->gameState->consoleHistory);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $this->getBrowserCommandLine());
    }
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

    $setupCommands = array("north"); //go north before going east
    $commandList = array("west", "w", "WEST", "West", "wEsT", "W", "moveWest();");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertEquals("kitchen", $gameEngine->gameState->getAvatarRoom()->name);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $gameEngine->gameState->consoleHistory);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $this->getBrowserCommandLine());
    }
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

    $setupCommands = array("north", "west"); //go north then west before going east
    $commandList = array("east", "e", "EAST", "East", "eASt", "E", "moveEast();");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertEquals("hall", $gameEngine->gameState->getAvatarRoom()->name);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $gameEngine->gameState->consoleHistory);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $this->getBrowserCommandLine());
    }
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

    $setupCommands = array("north"); //go north before going north
    $commandList = array("north", "n", "NORTH", "North", "noRTh", "N", "moveNorth();");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertContains("You cannot go north.", $gameEngine->gameState->consoleHistory);
      $this->assertContains("You cannot go north.", $this->getBrowserCommandLine());
      $this->assertEquals("hall", $gameEngine->gameState->avatarLocation);
    }
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

    $setupCommands = array(); //no setup, just go south
    $commandList = array("south", "s", "SOUTH", "South", "soUTh", "S", "moveSouth();");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertContains("You cannot go south.", $gameEngine->gameState->consoleHistory);
      $this->assertContains("You cannot go south.", $this->getBrowserCommandLine());
      $this->assertEquals("entrance", $gameEngine->gameState->avatarLocation);
    }
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

    $setupCommands = array(); //no setup, just go west
    $commandList = array("west", "w", "WEST", "West", "wEsT", "W", "moveWest();");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertContains("You cannot go west.", $gameEngine->gameState->consoleHistory);
      $this->assertContains("You cannot go west.", $this->getBrowserCommandLine());
      $this->assertEquals("entrance", $gameEngine->gameState->avatarLocation);
    }
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

    $setupCommands = array(); //no setup, just go east
    $commandList = array("east", "e", "EAST", "East", "eASt", "E", "moveEast();");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertContains("You cannot go east.", $gameEngine->gameState->consoleHistory);
      $this->assertContains("You cannot go east.", $this->getBrowserCommandLine());
      $this->assertEquals("entrance", $gameEngine->gameState->avatarLocation);
    }
  }

  public function testExit()
  {
    //User Story: Exit Verb
    //Given:
    //      Game running on any map,
    //      Player is in anywhere in the game
    //Trigger:
    //      Player exits game by typing exit and pressing the [Enter] key.
    //Verification/Then:
    //      Game exits.

    $setupCommands = array(); //no setup, just go exit
    $commandList = array("exit", "EXIT", "Exit", "eXiT", "eXIt", "exIt", "System.exit(0);");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertTrue($gameEngine->gameState->isExiting);
    }
  }

  public function testRestart()
  {
    //User Story: Restart Verb
    //Given:
    //      Game running on any map,
    //      Player is anywhere in the game (except for the initial state).
    //Trigger:
    //      Player resets the game by typing reset and pressing the [Enter] key.
    //Verification/Then:
    //      Game resets back to the initial game state.

    $setupCommands = array("north", "west"); //go to kitchen
    $commandList = array("reset", "reSET", "RESET", "REset", "reSTART", "restart", "REStart", "rESeT", "Restart", "Reset");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertFalse($gameEngine->gameState->isExiting);
      $this->assertContains("Game restarted.", $this->getBrowserCommandLine());
      $this->assertEquals("entrance", $gameEngine->gameState->avatarLocation);
      $this->assertContains($gameEngine->gameState->getAvatarRoom()->description, $this->getBrowserCommandLine());
    }
  }

  public function testHelp()
  {
    //User Story: Help Verb
    //Given:
    //      Game running on any map,
    //      Player is anywhere in the game.
    //Trigger:
    //      Player requests help by typing help and pressing the [Enter] key.
    //Verification/Then:
    //      Game displays help screen to player.

    $setupCommands = array(); //no setup, just help
    $commandList = array("?", "HELP", "help", "Help", "hELp", "heLP", "HElp");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertContains("HELP MENU", $gameEngine->gameState->consoleHistory);
    }
  }

  public function testUnknownCommand()
  {
    //User Story: Unknown Command
    //Given:
    //      Game running on any map,
    //      Player is in anywhere in the game,
    //Trigger 1:
    //      Player types no command and presses the [Enter] key.
    //Verification 1/Then 1:
    //      Game ignores input, awaits further input from Player.

    $setupCommands = array(); //no setup, just run empty strings
    $commandList = array("", "\n", "\t", "   ", "\t  \n", "\n\t\n  \n\t\n");

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertNotContains("I do not understand.", $gameEngine->gameState->consoleHistory);
      $this->assertNotContains("I do not understand.", $this->getBrowserCommandLine());
    }

    //User Story: Unknown Command
    //Given:
    //      Game running on any map,
    //      Player is in anywhere in the game,
    //Trigger 2:
    //      Player types something that is NOT a command and presses the [Enter] key.
    //Verification 2/Then 2:
    //      Game provides error message such as "I do not understand."

    $setupCommands = array(); //no setup, just run bad commands
    $commandList = array(
      "no", "nor", "nort", "so", "sou", "sout", "ea", "eas", "we", "wes",
      "movenorth();", "movesouth();", "moveeast();", "movewest();",
      "MOVENORTH();", "MOVESOUTH();", "MOVEEAST();", "MOVEWEST();",
      "moveNorth()", "moveSouth()", "moveEast()", "moveWest()",
      "north pole", "southern comfort", "eastern star", "1,000 ways to die in the west",
      "ex", "exi", "system.exit(0);", "SYSTEM.EXIT(0);", "System.exit(0)",
      "re", "res", "rese", "rest", "resta", "restar",
      "abcd", "abc", "ab", "123", "1", "12", "aoeu", "whoami", "blahblah",
      "i need help", "where\'s the exit", "restart me", "help me",
      "exit now", "reset the game please"
    );

    foreach ($commandList as $command)
    {
      //run server-side setup (test control)
      $gameEngine = new GameEngine();
      //run client-side setup (test variable)
      $this->deleteAllVisibleCookies();
      $this->open("http://localhost/Game/index.php");
      //run setup commands
      foreach ($setupCommands as $sCommand)
      {
        $this->assertBrowserAndServerOutputMatch($gameEngine, $sCommand);
      }
      //run code on both server and client
      $this->assertBrowserAndServerOutputMatch($gameEngine, $command);
      $this->assertEquals("entrance", $gameEngine->gameState->avatarLocation);
      $this->assertContains("I do not understand.", $gameEngine->gameState->consoleHistory);
      $this->assertContains("I do not understand.", $this->getBrowserCommandLine());
    }
  }
}
