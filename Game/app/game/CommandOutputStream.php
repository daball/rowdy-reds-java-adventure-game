<?php

namespace game;

require_once __DIR__.'/../player/OutputStream.php';

class CommandOutputStream extends OutputStream
{
  private $gameEngine;

  public function __construct()
  {
  }

  public function write_any($value)
  {

  }
}
