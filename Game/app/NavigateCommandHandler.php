<?php

require_once 'CommandHandlerInterface.php';

///Handles navigation commands.
class NavigateCommandHandler extends CommandHandlerInterface
{
  ///Validates the incoming command line for navigation commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($gameState, $commandLine)
  {
    $commandLine = strtolower($commandLine);
    return  $commandLine == 'north' ||
            $commandLine == 'n' ||
            $commandLine == 'south' ||
            $commandLine == 's' ||
            $commandLine == 'east' ||
            $commandLine == 'e' ||
            $commandLine == 'west' ||
            $commandLine == 'w';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($gameState, $commandLine)
  {
    $gameState->moves++;
    return $gameState->navigate($commandLine);
  }
}
