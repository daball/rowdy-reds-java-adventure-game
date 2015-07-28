<?php

require_once 'GameState.php';

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
    session_start();
    if (isset($_SESSION['gameState']))
      $this->restoreSession();
    else
      $this->createSession();
    $this->commandProcessor = new CommandProcessor($this->gameState);
  }

  public function __destruct()
  {
    $this->saveSession();
  }
}

new GameEngine();
