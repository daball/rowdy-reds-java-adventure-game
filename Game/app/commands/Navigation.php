<?php

namespace commands;
use engine\CommandProcessor;
use engine\GameState;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

///Handles navigation commands.
class NavigateCommandHandler extends BaseCommandHandler
{
  ///Validates the incoming command line for navigation commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine)
  {
    return  strtolower($commandLine) == 'n' ||
            strtolower($commandLine) == 'north' ||
            $commandLine == 'moveNorth();' ||
            $commandLine == 'me.moveNorth();' ||

            strtolower($commandLine) == 's' ||
            strtolower($commandLine) == 'south' ||
            $commandLine == 'moveSouth();' ||
            $commandLine == 'me.moveSouth();' ||

            strtolower($commandLine) == 'e' ||
            strtolower($commandLine) == 'east' ||
            $commandLine == 'moveEast();' ||
            $commandLine == 'me.moveEast();' ||

            strtolower($commandLine) == 'w' ||
            strtolower($commandLine) == 'west' ||
            $commandLine == 'moveWest();' ||
            $commandLine == 'me.moveWest();' ||

            strtolower($commandLine) == 'u' ||
            strtolower($commandLine) == 'up' ||
            $commandLine == 'moveUp();' ||
            $commandLine == 'me.moveUp();' ||

            strtolower($commandLine) == 'down' ||
            strtolower($commandLine) == 'd';
            $commandLine == 'moveDown();' ||
            $commandLine == 'me.moveDown()';
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
      case 'moveUp();':
      case 'me.moveUp();':
        $commandLine = 'u';
        break;
      case 'moveDown();':
      case 'me.moveDown();':
        $commandLine = 'd';
        break;
    }
    $gameState->moves++;
    return $gameState->getPlayer()->navigate($commandLine);
  }
}

CommandProcessor::addCommandHandler(new NavigateCommandHandler());
