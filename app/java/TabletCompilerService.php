<?php

//TODO: determine which deployment to use based on APPLICATION_ENV parameter
//require_once("http://localhost:8081/cc-development/java/Java.inc");
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
    if (DIRECTORY_SEPARATOR != "/")
      $cachePath = trim(str_replace("/", DIRECTORY_SEPARATOR, $cachePath), "\\");
    $classPath = Path::combine(__DIR__, "..", "..", "java", "war-template", "WEB-INF", "lib", "edu.radford.rowdyred.jar");
    if (DIRECTORY_SEPARATOR != "/")
      $classPath = trim(str_replace("/", DIRECTORY_SEPARATOR, $classPath), "\\");
    $this->javaCompilerService = new java("edu.radford.rowdyred.internal.TabletCompilerService", session_id(), $cachePath, $classPath, null);
  }

  public function compile($constructorCode, $sourceCode) {
    return $this->javaCompilerService->compile($constructorCode, $sourceCode);
  }

  public function clean() {
    return $this->javaCompilerService->clean();
  }

  public function invoke($methodName, $parameters) {
    return $this->javaCompilerService->invokeMethod($methodName, $parameters);
  }

  public function getInstance() {
    return $this->javaCompilerService->tabletInstance;
  }

  public function getClass() {
    return $this->javaCompilerService->compiledClass;
  }

  public function getConsoleOutput() {
    return java_values($this->javaCompilerService->getConsoleOutput());
  }
}
