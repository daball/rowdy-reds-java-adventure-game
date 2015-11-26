<?php

// namespace commands;
// use engine\CommandProcessor;
// use engine\GameState;
//
// require_once __DIR__.'/../engine/GameState.php';
// require_once __DIR__.'/../engine/CommandProcessor.php';
// require_once 'BaseCommandHandler.php';
//
// class MethodParameter
// {
//   public $type;
//   public $name;
//
//   public function __construct($type, $name) {
//     $this->type = $type;
//     $this->name = $name;
//   }
// }
//
// class Method
// {
//   public $type;
//   public $name;
//   public $parameters;
//   public $code;
//
//   public function __construct($type, $name, $code="", $parameters=null) {
//     $this->type = $type;
//     $this->name = $name;
//     $this->code = $code;
//     $this->parameters = ($parameters ? $parameters : array());
//   }
//
//   public function addParameter($type, $name) {
//     array_push($this->parameters, new TabletMethodParameter($type, $name));
//     return $this;
//   }
//
//   public function setCode($code) {
//     $this->code = code;
//     return $this;
//   }
// }
//
// class MethodCallParameter
// {
//   public $parameter;
//   public function __construct($parameter) {
//     $this->parameter = $parameter;
//   }
// }
//
// class MethodCall
// {
//   public $name;
//   public $parameters;
//
//   public function __construct($name, $parameters = null) {
//     $this->name = $name;
//     $this->parameters = ($parameters ? $parameters : array());
//   }
//
//   public function addParameter($parameter) {
//     array_push($this->parameters, new TabletMethodCallParameter($parameter));
//     return $this;
//   }
// }
//
// class TabletCall extends BaseCommandHandler
// {
//   public $REGEX_METHODS = '/([A-Za-z$_]{1}[A-Za-z0-9$_]*)\s+([A-Za-z$_]{1}[A-Za-z0-9$_]*)\s*\((.*)\)/';
//   public $REGEX_METHOD_PARAMETERS = '/([A-Za-z$_]{1}[A-Za-z0-9$_]*)\s+([A-Za-z$_]{1}[A-Za-z0-9$_]*)/';
//   public $REGEX_METHOD_CALL = '/([A-Za-z$_]{1}[A-Za-z0-9$_]*)\s*\((.*)\)\s*;/';
//   public $REGEX_METHOD_CALL_PARAMETERS = '/([\'\"A-Za-z0-9$_\\\(\)\[\]\s]+)/';
//
//   public function stripComments($sourceCode) {
//     $quotes = array();
//     $insideMultiline = false;
//     $insideSingleline = false;
//     $output = "";
//     for ($at = 0; $at < strlen($sourceCode); $at++) {
//       if (substr($sourceCode, $at, 1) === "\""
//         || substr($sourceCode, $at, 1) === "'")
//       {
//         if (count($quotes) == 0
//           || substr($sourceCode, $at-1, 1) !== "\\")
//           array_push($quotes, substr($sourceCode, $at, 1));
//       }
//       else if (!count($quotes)
//             && $at < strlen($sourceCode)-1)
//       {
//         if (substr($sourceCode, $at, 2) == '/*'
//           && !$insideSingleline
//           && !$insideMultiline)
//         {
//           $insideMultiline = true;
//           $at++;
//         }
//         else if (substr($sourceCode, $at, 2) == '//'
//               && !$insideMultiline)
//         {
//           $insideSingleline = true;
//           $at += 2;
//         }
//         else if (substr($sourceCode, $at, 2) == '*/'
//           && $insideMultiline)
//         {
//           $insideMultiline = false;
//           $at+=2;
//         }
//         else if ((substr($sourceCode, $at, 1) == "\n"
//           || substr($sourceCode, $at, 1) == "\r")
//           && $insideSingleline)
//         {
//           $insideSingleline = false;
//         }
//       }
//       if (!$insideMultiline
//         && !$insideSingleline)
//         $output .= substr($sourceCode, $at, 1);
//     }
//     return $output;
//   }
//
//   public function getAllMethodCallParameters($parametersString)
//   {
//     $matches = array();
//     $parameters = array();
//     preg_match_all($this->REGEX_METHOD_CALL_PARAMETERS, $parametersString, $matches);
//     for ($m = 0; $m < count($matches[0]); $m++) {
//       $parameter = trim($matches[1][$m]);
//       $parameter = new MethodCallParameter($parameter);
//       array_push($parameters, $parameter);
//     }
//     return $parameters;
//   }
//
//   public function getAllMethodParameters($parametersString)
//   {
//     $matches = array();
//     $parameters = array();
//     preg_match_all($this->REGEX_METHOD_PARAMETERS, $parametersString, $matches);
//     for ($m = 0; $m < count($matches[0]); $m++) {
//       $type = trim($matches[1][$m]);
//       $name = trim($matches[2][$m]);
//       $parameter = new MethodParameter($type, $name);
//       array_push($parameters, $parameter);
//     }
//     return $parameters;
//   }
//
//   public function getAllMethods($tabletCode)
//   {
//     $matches = array();
//     $methods = array();
//     preg_match_all($this->REGEX_METHODS, $tabletCode, $matches);
//     for ($m = 0; $m < count($matches[0]); $m++) {
//       $type = trim($matches[1][$m]);
//       $name = trim($matches[2][$m]);
//       $parametersString = trim($matches[3][$m]);
//       $parameters = $this->getAllMethodParameters($parametersString);
//       $code = $this->getMethodCode($parametersString, $name);
//       //
//       //   foreach ($parameterMatches)
//       //
//       //   //char-by-char, increment count on {, decrement count on }, when } is 0, stop there
//       //
//       $tabletMethod = new Method($type, $name, $code, $parameters);
//       array_push($methods, $tabletMethod);
//     }
//     return $methods;
//   }
//
//   public function getMethodCode($tabletCode, $methodName)
//   {
//   }
//
//   public function getMethodCall($commandLine)
//   {
//     $commandLine = $this->stripComments($commandLine);
//     $match = array();
//     preg_match($this->REGEX_METHOD_CALL, $commandLine, $match);
//     if (count($match)) {
//       $name = $match[1];
//       $parametersString = $match[2];
//       $parameters = $this->getAllMethodCallParameters($parametersString);
//       $call = new MethodCall($name, $parameters);
//       return $call;
//     }
//   }
//
//   public function getMethod($tabletCode, $methodName)
//   {
//     $tabletCode = $this->stripComments($tabletCode);
//     $methods = $this->getAllMethods($tabletCode);
//     foreach ($methods as $method)
//       if ($method->name == $methodName)
//         return $method;
//
//     return null;
//   }
//
//   public function verifyCallMatchesDefinition($method, $methodCall) {
//     if (count($method->parameters) !== count($methodCall->parameters))
//       return false;
//     return true;
//   }
//
//   public function validateCommand($commandLine, $tabletCode)
//   {
//     $methodCall = $this->getMethodCall($commandLine);
//     return $methodCall != null
//         && $this->getMethod($tabletCode, $methodCall->name) != null;
//   }
//
//   public function executeCommand($commandLine, $tabletCode)
//   {
//     $methodCall = $this->getMethodCall($commandLine);
//     $method = $this->getMethod($tabletCode, $methodCall->name);
//     $isValidCall = $this->verifyCallMatchesDefinition($method, $methodCall);
//
//     $output = "Attempt to invoke method from tablet:\n"
//             . "  Method Name: " . $methodCall->name . "\n"
//             . "  Parameters (" . count($methodCall->parameters) . "):\n";
//     foreach ($methodCall->parameters as $parameter) {
//       $output .= "    Parameter Value: " . $parameter->parameter . "\n";
//     }
//     $output .= "This call pattern matches the method definition in tablet:\n"
//             . "  Method Name: " . $method->name . "\n"
//             . "  Parameters (" . count($method->parameters) . "):\n";
//     foreach ($method->parameters as $parameter) {
//       $output .= "    Parameter Type: " . $parameter->type . "\n";
//       $output .= "    Parameter Name: " . $parameter->name . "\n\n";
//     }
//     if ($isValidCall) {
//       $output .= "This is a valid call.\n";
//       if (count($methodCall->parameters) && count($method->parameters)) {
//         $output .= "When matched, the following should occur:\n";
//         for ($p = 0; $p < count($methodCall->parameters) && $p < count($method->parameters); $p++) {
//           $output .= "    Parameter Name: " . $method->parameters[$p]->name . "\n";
//           $output .= "    Parameter Value: " . $methodCall->parameters[$p]->parameter . "\n\n";
//         }
//       }
//     }
//     else
//       $output .= "This is not a valid call.\n";
//     return $output;
//   }
// }
//
// CommandProcessor::addCommandHandler(new TabletCall());
