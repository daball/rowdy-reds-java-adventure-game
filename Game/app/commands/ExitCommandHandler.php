<?php

namespace commands;
use game\CommandProcessor;

require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

///Handles exit command.
class ExitCommandHandler extends BaseCommandHandler
{
  ///Validates the incoming command line for exit commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($gameState, $commandLine)
  {
    return strtolower($commandLine) == 'exit';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($gameState, $commandLine)
  {
    $gameState->resetGameState();
    $eol = "\n";
    $this->consoleHistory = "Game started." . $eol . $gameState->getPlayerRoom()->inspect();
    $gameState->isExiting = true;
  }
}

CommandProcessor::addCommandHandler(new ExitCommandHandler());
