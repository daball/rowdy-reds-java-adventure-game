<?php

namespace commands;
use game\CommandProcessor;

require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

///Handles reset command.
class ResetCommandHandler extends BaseCommandHandler
{
  ///Validates the incoming command line for reset commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine)
  {
    $commandLine = strtolower($commandLine);
    return  $commandLine == 'reset' ||
            $commandLine == 'restart';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($commandLine)
  {
    $gameState = GameState::getGameState();
    $message = $gameState->resetGameState();
    return $message;
  }
}

CommandProcessor::addCommandHandler(new ResetCommandHandler());
