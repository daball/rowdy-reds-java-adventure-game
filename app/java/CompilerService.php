<?php

//TODO: determine which deployment to use based on APPLICATION_ENV parameter
require_once("http://localhost:8081/cc-development/java/Java.inc");

class CompilerService {
  private $javaCompilerService = null;
  private $sourceCode = "";

  public function __construct($sourceCode) {
    $this->sourceCode= $sourceCode;
    $this->javaCompilerService = new java("edu.radford.rowdyred.internal.CompilerService", array($sourceCode));
  }

  public function compile() {
    $this->javaCompilerService->compile();
  }

  public function invokeMethod($methodName, $parameters) {
    $this->javaCompilerService->invokeMethod($methodName, $parameters);
  }
}

$Player = new java("RowdyRed.Player");
echo $Player->inspect('something');
echo $Player->equip('something else');
