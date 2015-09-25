<?php

namespace game;

require_once 'GameState.php';
require_once 'CommandProcessor.php';
require_once __DIR__.'/../commands/index.php';

///GameEngine manages the game state across many page loads
///using PHP sessions. Typically sessions work by sending out
///a session cookie, which restores the session from the PHP
///session backing store (which is sometimes a session database).
///GameEngine also dispatches command line input to command line
///handlers and updates the game state when command is done executing.
class GameEngine
{
  public $gameState;
  public $commandProcessor;

  public function createSession()
  {
    $this->gameState = new GameState();
    $_SESSION['gameState'] = $this->gameState->serialize();
  }

  public function restoreSession()
  {
    $this->gameState = new GameState($_SESSION['gameState']);
  }

  public function saveSession()
  {
    $_SESSION['gameState'] = $this->gameState->serialize();
  }

  public function __construct()
  {
    //check if existing gameState exists in session
    if (isset($_SESSION['gameState']))
      //if so, restore the gameState session
      $this->restoreSession();
    else
      //otherwise, create a new gameState session
      $this->createSession();
    //create command processor, which will execute any command on the $_POST['commandLine']
    $this->commandProcessor = new CommandProcessor($this->gameState);
  }
}
