<?php

//TODO: determine which deployment to use based on APPLICATION_ENV parameter
//require_once("http://localhost:8081/cc-development/java/Java.inc");

// class CompilerException extends Exception {
//   public function __construct($message) {
//     parent::_construct($message);
//   }
// }

class TabletCompilerService {
  private $javaCompilerService = null;
  public $sourceCode = "";

  public function __construct($sourceCode) {
    $this->javaCompilerService = new java("edu.radford.rowdyred.internal.TabletCompilerService", session_id(), null, $sourceCode);
    $this->sourceCode = $this->javaCompilerService->sourceCode;
  }

  public function compile() {
    $this->javaCompilerService->compile();
  }

  public function invoke($methodName, $parameters) {
    $this->javaCompilerService->invokeMethod($methodName, $parameters);
  }
}

// $Player = new java("RowdyRed.Player");
// echo $Player->inspect('something');
// echo $Player->equip('something else');
