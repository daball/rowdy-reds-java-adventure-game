<?php

require_once __DIR__.'/../../vendor/autoload.php';

use PhpDocReader\PhpDocReader;

/**
 * MyPhpDocReader is an extremely simplified version of php-di/php-docreader.
 * I specifically wanted to remove all of the validation criteria because
 * we are going to be using it simulate JavaDoc documentation, which doesn't
 * necessarily have any actual relevance in PHP. So we may lie about the types
 * we are using for the sake of simulating Java. It does this by overriding the
 * default implementations. I also had to add an additional method, getMethodReturnClass
 * in order to get the return type of methods.
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 * @author David Ball <david@daball.me>
 */
class MyPhpDocReader extends PhpDocReader {

  public function getDocText($object) {
    $doc = $object->getDocComment();
    $doc = str_replace('\r\n', '\n', $doc);
    $doc = explode("\n", $doc);
    for ($l = 0; $l < count($doc); $l++){
      $doc[$l] = trim($doc[$l], "/* \t\r\n");
    }
    $doc = implode(' ', $doc);
    $firstAnnotation = strpos($doc, '@');
    if ($firstAnnotation === FALSE)
      return trim($doc);
    else
      return trim(substr($doc, 0, $firstAnnotation));
  }

  /**
    * Parse the docblock of the property to get the class of the ignore annotation.
    *
    * @param ReflectionProperty $property
    *
    * @return bool
    */
   public function isIgnored($object) {
     // Get the content of the @var annotation
     if (preg_match('/@ignore/', $object->getDocComment(), $matches)) {
       return true;
     } else {
       return false;
     }
   }

  /**
    * Parse the docblock of the property to get the class of the var annotation.
    *
    * @param ReflectionProperty $property
    *
    * @return string|null Type of the property (content of var annotation)
    */
   public function getPropertyClass(ReflectionProperty $property) {
     // Get the content of the @var annotation
     if (preg_match('/@var\s+([^\s]+)/', $property->getDocComment(), $matches)) {
       list(, $type) = $matches;
     } else {
       return null;
     }
     $type = ltrim($type, '\\');
     return $type;
   }

   /**
    * Parse the docblock of the property to get the class of the param annotation.
    *
    * @param ReflectionParameter $parameter
    *
    * @return string|null Type of the property (content of var annotation)
    */
   public function getParameterClass(ReflectionParameter $parameter) {
     // Use reflection
     $parameterClass = $parameter->getClass();
     if ($parameterClass !== null) {
       return $parameterClass->name;
     }
     $parameterName = $parameter->name;
     // Get the content of the @param annotation
     $method = $parameter->getDeclaringFunction();
     if (preg_match('/@param\s+([^\s]+)\s+\$' . $parameterName . '/', $method->getDocComment(), $matches)) {
       list(, $type) = $matches;
     } else {
       return null;
     }
     $type = ltrim($type, '\\');
     return $type;
   }

   /**
    * Parse the docblock of the property to get the class of the return annotation.
    *
    * @param ReflectionMethod $method
    *
    * @return string|null Type of the property (content of var annotation)
    */
   public function getMethodReturnClass(ReflectionMethod $method) {
     // Get the content of the @param annotation
     if (preg_match('/@return\s+([^\s]+)/', $method->getDocComment(), $matches)) {
         list(, $type) = $matches;
     } else {
         return null;
     }
    $type = ltrim($type, '\\');
    return $type;
  }
}
