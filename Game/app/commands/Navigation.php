<?php

namespace commands;
use game\CommandProcessor;

require_once __DIR__.'/../game/GameState.php';
require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

///Handles navigation commands.
class NavigateCommandHandler extends BaseCommandHandler
{
  ///Validates the incoming command line for navigation commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine)
  {
    return  strtolower($commandLine) == 'north' ||
            $commandLine == 'moveNorth();' ||
            $commandLine == 'me.moveNorth();' ||
            strtolower($commandLine) == 'n' ||
            strtolower($commandLine) == 'south' ||
            $commandLine == 'moveSouth();' ||
            $commandLine == 'me.moveSouth();' ||
            strtolower($commandLine) == 's' ||
            strtolower($commandLine) == 'east' ||
            $commandLine == 'moveEast();' ||
            $commandLine == 'me.moveEast();' ||
            strtolower($commandLine) == 'e' ||
            strtolower($commandLine) == 'west' ||
            $commandLine == 'moveWest();' ||
            $commandLine == 'me.moveWest();' ||
            strtolower($commandLine) == 'w';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($commandLine)
  {
    $gameState = GameState::getGameState();
    switch ($commandLine)
    {
      case 'moveNorth();':
      case 'me.moveNorth();':
        $commandLine = 'n';
        break;
      case 'moveSouth();':
      case 'me.moveSouth();':
        $commandLine = 's';
        break;
      case 'moveEast();':
      case 'me.moveEast();':
        $commandLine = 'e';
        break;
      case 'moveWest();':
      case 'me.moveWest();':
        $commandLine = 'w';
        break;
    }
    $gameState->moves++;
    return $gameState->getPlayer()->navigate($commandLine);
  }
}

CommandProcessor::addCommandHandler(new NavigateCommandHandler());
