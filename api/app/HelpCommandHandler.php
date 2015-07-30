<?php

require_once 'CommandHandlerInterface.php';

///Handles help command.
class HelpCommandHandler extends CommandHandlerInterface
{
  ///Validates the incoming command line for help commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($gameState, $commandLine)
  {
    $commandLine = strtolower($commandLine);
    return  $commandLine == 'help' ||
            $commandLine == '?';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($gameState, $commandLine)
  {
    $eol = "\n";
    return  "********* HELP MENU *********" . $eol
          . $eol
          . "Goal: Explore our tiny castle." . $eol
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
