<?php

namespace commands;
use game\CommandProcessor;

require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';
require_once __DIR__.'/../java/JavaReflection.php';

///Handles help command.
class JavadocCommandHandler extends BaseCommandHandler
{
  private function isClassSpecified($commandLine)
  {
    return strlen($commandLine) > 8;
  }

  private function getClassName($commandLine)
  {
    return trim(substr($commandLine, 8));
  }

  ///Validates the incoming command line for help commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($gameState, $commandLine)
  {
    return stripos($commandLine, 'javadoc') === 0;
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($gameState, $commandLine)
  {
    $eol = "\n";
    if ($this->isClassSpecified($commandLine)) {
      $className = $this->getClassName($commandLine);
      if ($gameState->isKnownAPIClass($className))
      {
        $javadoc = JavaReflection::javadoc($className);
        return $javadoc;
      }
      else
      {
        return "I don't know anything about a $className.";
      }
    }
    else
      return "javadoc: work in progress";
  }
}

CommandProcessor::addCommandHandler(new JavadocCommandHandler());
