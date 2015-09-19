<?php

require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

///Handles navigation commands.
class NavigateCommandHandler extends BaseCommandHandler
{
  ///Validates the incoming command line for navigation commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($gameState, $commandLine)
  {
    return  strtolower($commandLine) == 'north' ||
            $commandLine == 'moveNorth();' ||
            strtolower($commandLine) == 'n' ||
            strtolower($commandLine) == 'south' ||
            $commandLine == 'moveSouth();' ||
            strtolower($commandLine) == 's' ||
            strtolower($commandLine) == 'east' ||
            $commandLine == 'moveEast();' ||
            strtolower($commandLine) == 'e' ||
            strtolower($commandLine) == 'west' ||
            $commandLine == 'moveWest();' ||
            strtolower($commandLine) == 'w';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($gameState, $commandLine)
  {
    switch ($commandLine)
    {
      case 'moveNorth();':
        $commandLine = 'n';
        break;
      case 'moveSouth();':
        $commandLine = 's';
        break;
      case 'moveEast();':
        $commandLine = 'e';
        break;
      case 'moveWest();':
        $commandLine = 'w';
        break;
    }
    $gameState->moves++;
    return $gameState->navigate($commandLine);
  }
}

CommandProcessor::addCommandHandler(new NavigateCommandHandler());
