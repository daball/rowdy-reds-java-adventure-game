<?php

namespace commands;
use engine\CommandProcessor;
use engine\GameState;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

///Handles exit command.
class ExitCommandHandler extends BaseCommandHandler
{
  ///Validates the incoming command line for exit commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine)
  {
    return strtolower($commandLine) == 'exit' || $commandLine == "System.exit(0);";
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($commandLine)
  {
    $gameState = GameState::getGameState();
    $gameState->resetGameState();
    $eol = "\n";
    $this->consoleHistory = "Game started." . $eol . $gameState->getPlayerRoom()->inspect();
    $gameState->isExiting = true;
  }
}

CommandProcessor::addCommandHandler(new ExitCommandHandler());
