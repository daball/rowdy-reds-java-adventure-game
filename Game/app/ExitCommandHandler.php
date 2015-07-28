<?php

require_once 'CommandHandlerInterface.php';

///Handles exit command.
class ExitCommandHandler extends CommandHandlerInterface
{
  ///Validates the incoming command line for exit commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($gameState, $commandLine)
  {
    $commandLine = strtolower($commandLine);
    return  $commandLine == 'exit' ||
            $commandLine == 'System.exit(0);';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($gameState, $commandLine)
  {
    $gameState->resetGameState();
    $eol = "\n";
    $this->consoleHistory = "Game started." . $eol . $gameState->inspectRoom();
    $gameState->isExiting = true;
  }
}
