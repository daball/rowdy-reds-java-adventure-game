<?php

///Extend this class and override the base functionality for
///each command handler.
abstract class BaseCommandHandler
{
  ///Validates the incoming command line.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($gameState, $commandLine)
  {
    //should return true if the command line is
    //valid for the command handler
    return false;
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($gameState, $commandLine)
  {
    //should return the output of the command
    return "";
  }
}
