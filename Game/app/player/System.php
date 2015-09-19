<?php

require_once 'OutputStream.php';
require_once 'PrintStream.php';

class System
{
  /**
   * @var PrintStream
   **/
  public static $out = null;

  /**
   * @ignore
   **/
  public static $gameState = null;

  /**
   * @param int $exitCode
   * @return void
   **/
  public static function JAVA_exit($exitCode)
  {
    new ExitCommandHandler($gameState, 'exit');
  }

  public static function init($gameState) {
    self::$gameState = $gameState;
    self::$out = new PrintStream(self::$gameState);
  }
}
