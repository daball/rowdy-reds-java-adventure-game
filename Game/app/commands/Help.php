<?php

namespace commands;
use engine\CommandProcessor;
use engine\GameState;

require_once __DIR__.'/../engine/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

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
    return  "********* HELP MENU *********" . $eol
          . $eol
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
          . "north, n, or moveNorth();" . $eol
          . $eol
          . "Move south by typing:" . $eol
          . "south, s, or moveSouth();" . $eol
          . $eol
          . "Move west by typing:" . $eol
          . "west, w, moveWest();" . $eol
          . $eol
          . "Move east by typing:" . $eol
          . "east, e, moveEast();" . $eol
          . $eol
          . "********** END HELP **********";
  }
}

CommandProcessor::addCommandHandler(new HelpCommandHandler());
