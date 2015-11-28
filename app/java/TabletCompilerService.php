<?php

//TODO: determine which deployment to use based on APPLICATION_ENV parameter
require_once("http://localhost:8081/cc-development/java/Java.inc");
require_once(__DIR__."/../util/Path.php");

// class CompilerException extends Exception {
//   public function __construct($message) {
//     parent::_construct($message);
//   }
// }

class TabletCompilerService {
  private $javaCompilerService = null;
  public $sourceCode = "";

  public function __construct() {
    $cachePath = Path::combine(__DIR__, "..", "..", "__player_tablet_cache");
    $this->javaCompilerService = new java("edu.radford.rowdyred.internal.TabletCompilerService", session_id(), $cachePath, null);
  }

  public function compile($sourceCode) {
    return $this->javaCompilerService->compile($sourceCode);
  }

  public function invoke($methodName, $parameters) {
    return $this->javaCompilerService->invokeMethod($methodName, $parameters);
  }
}

// $Player = new java("RowdyRed.Player");
// echo $Player->inspect('something');
// echo $Player->equip('something else');
