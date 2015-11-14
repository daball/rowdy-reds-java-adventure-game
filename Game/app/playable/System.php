<?php

namespace playable;

use \commands\ExitCommandHandler;

require_once 'OutputStream.php';
require_once 'PrintStream.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once __DIR__.'/../commands/Exit.php';

class System
{
  /**
   * @var PrintStream
   **/
  public static $out = null;

  /**
   * @param int $exitCode
   * @return void
   **/
  public static function JAVA_exit($exitCode)
  {
    new ExitCommandHandler('exit');
  }

  public static function init() {
    self::$out = new PrintStream();
  }
}

System::init();
