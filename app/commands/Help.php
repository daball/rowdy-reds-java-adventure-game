<?php

namespace commands;

require_once __DIR__.'/../engine/CommandProcessor.php';
require_once __DIR__.'/../engine/GameEngine.php';
require_once 'BaseCommandHandler.php';

use engine\CommandProcessor;
use engine\GameEngine;

///Handles help command.
class HelpCommandHandler extends BaseCommandHandler
{
  ///Validates the incoming command line for help commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine)
  {
    $commandLine = strtolower($commandLine);
    return  $commandLine == 'help' ||
            $commandLine == '?';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($commandLine)
  {
    $eol = "\n";
    $isDevMode = GameEngine::isApplicationEnv("development");
    $devCommands = "DEVELOPMENT MODE ONLY:$eol"
                  ."goto - lists all rooms you can go to$eol"
                  ."goto [room name] - enters a room, despite any game logic$eol$eol";
    return  "********* HELP MENU *********" . $eol
          . $eol
          . ($isDevMode?$devCommands:"")
          . "Goal: Explore our tiny castle." . $eol
          . $eol
          . "New experimental commands:" . $eol
          . "  javadoc - list of known API classes in your javadoc notebook$eol"
          . "  javadoc ClassName - displays documentation about the API class$eol"
          . "  locals - displays list of variables is the player variable bag$eol"
          . "  gc - garbage collects a local variable the player has made$eol"
          . "  globals - displays list of variables available anywhere in the game$eol"
          . "  User can instantiate any class for which an API class has been found to a local variable.$eol"
          . $eol
          . "HELP displays this help screen." . $eol
          . $eol
          . "Restart the game by typing:" . $eol
          . "restart or reset" . $eol
          . $eol
          . "Exit the game by typing:" . $eol
          . "exit or System.exit(0);" . $eol
          . $eol
          . "Move north by typing:" . $eol
          . "north, n, or moveNorth(); or me.moveNorth();" . $eol
          . $eol
          . "Move south by typing:" . $eol
          . "south, s, or moveSouth(); or me.moveSouth();" . $eol
          . $eol
          . "Move west by typing:" . $eol
          . "west, w, moveWest(); or me.moveWest();" . $eol
          . $eol
          . "Move east by typing:" . $eol
          . "east, e, moveEast(); or me.moveEast();" . $eol
          . $eol
          . "Move up by typing:" . $eol
          . "up, u, moveUp(); or me.moveUp();" . $eol
          . $eol
          . "********** END HELP **********";
  }
}

CommandProcessor::addCommandHandler(new HelpCommandHandler());
