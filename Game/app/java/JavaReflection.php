<?php

namespace java;
use \ReflectionClass;
use \ReflectionMethod;

require_once __DIR__.'/MyPhpDocReader.php';

class JavaReflection
{
  private static $docReader;

  private static function getDocReader() {
    if (self::$docReader == null)
      self::$docReader = new MyPhpDocReader(true);
    return self::$docReader;
  }

  private static function hasConstructor($class) {
    $count = 0;
    $methods = $class->getMethods();
    for ($m = 0; $count == 0 && $m < count($methods); $m++) {
      if (!self::getDocReader()->isIgnored($methods[$m]))
        if (strstr($methods[$m]->getName(), "__construct") !== FALSE)
          $count++;
    }
    return $count > 0;
  }

  private static function hasMethod($class) {
    $count = 0;
    $methods = $class->getMethods();
    for ($m = 0; $count == 0 && $m < count($methods); $m++) {
      if (!self::getDocReader()->isIgnored($methods[$m]))
        if (strstr($methods[$m]->getName(), "__construct") === FALSE)
          $count++;
    }
    return $count > 0;
  }

  private static function hasProperty($class) {
    $count = 0;
    $properties = $class->getProperties();
    for ($p = 0; $count == 0 && $p < count($properties); $p++) {
      if (!self::getDocReader()->isIgnored($properties[$p]))
        $count++;
    }
    return $count > 0;
  }

  private static function propertyAsString($property) {
    if (preg_match('/[jJ][aA][vV][aA]_([^\s]+)/', $property->getName(), $matches))
      list(, $name) = $matches;
    else
      $name = $property->getName();
    $propType = self::getDocReader()->getPropertyClass($property);
    $docs = self::getDocReader()->getDocText($property);
    return ($docs ? '// ' . $docs . "\n    " : "")
         . ($property->isPrivate() ? "private " : "")
         . ($property->isProtected() ? "protected " : "")
         . ($property->isPublic() ? "public " : "")
         . ($property->isStatic() ? "static " : "")
         . ($propType ? $propType . ' ' : '')
         . $name;
  }

  private static function methodParameterAsString($parameter) {
    if (preg_match('/[jJ][aA][vV][aA]_([^\s]+)/', $parameter->getName(), $matches))
      list(, $name) = $matches;
    else
      $name = $parameter->getName();
    $paramType = self::getDocReader()->getParameterClass($parameter);
    return ($paramType ? $paramType . ' ' : '') . $name;
  }

  private static function methodAsString($method) {
    if (preg_match('/[jJ][aA][vV][aA]_([^\s]+)/', $method->getName(), $matches))
      list(, $name) = $matches;
    else
      $name = $method->getName();
    if (strstr($name, "__construct") !== FALSE)
      $name = $method->getDeclaringClass()->getName();
    $methodReturns = self::getDocReader()->getMethodReturnClass($method);
    $docs = self::getDocReader()->getDocText($method);
    $output = ($docs ? '// ' . $docs . "\n    " : "")
            . ($method->isPrivate() ? "private " : "")
            . ($method->isProtected() ? "protected " : "")
            . ($method->isPublic() ? "public " : "")
            . ($method->isStatic() ? "static " : "")
            . ($methodReturns ? $methodReturns . ' ' : '')
            . $name
            . '(';
    foreach ($method->getParameters() as $parameter) {
      $output .= self::methodParameterAsString($parameter) . ', ';
    }
    $output = trim($output, ', ');
    $output .= ')';
    return $output;
  }

  public static function javadoc($argument) {
    $class = new \ReflectionClass($argument);
    $docs = self::getDocReader()->getDocText($class);
    $classType = $class->isInterface()?"interface":"class";
    $classType = ($class->isFinal()?"final $classType":$classType);
    $classType = (!$class->isInterface() && $class->isAbstract()?"abstract $classType":$classType);
    $doc = ($docs ? '// ' . $docs . "\n" : "")
         . "$classType " . $class->getShortName() . "\n";
    if (self::hasConstructor($class)) {
      $doc .= "  Constructor:\n";
      $constructors = array();
      foreach ($class->getMethods() as $method) {
        if (!self::getDocReader()->isIgnored($method))
          if (strstr($method->getName(), "__construct") !== FALSE)
            array_push($constructors, "    " . self::methodAsString($method) . ";\n");
      }
      $doc .= implode('', array_unique($constructors));
    }
    if (self::hasProperty($class)) {
      $doc .= "  Properties:\n";
      $properties = array();
      foreach ($class->getProperties() as $property) {
        if (!self::getDocReader()->isIgnored($property))
          array_push($properties, "    " . self::propertyAsString($property) . "\n");
      }
      $doc .= implode('', $properties);
    }
    if (self::hasMethod($class)) {
      $doc .= "  Methods:\n";
      $methods = array();
      foreach ($class->getMethods() as $method) {
        if (!self::getDocReader()->isIgnored($method))
          if ($method->getName() != "init" && $method->getName() != "__construct")
          array_push($methods, "    " . self::methodAsString($method) . "\n");
      }
      $doc .= implode('', $methods);
    }
    return $doc;
  }

  public static function getClassName($instance)
  {
    $class = new ReflectionClass($argument);
    return $class->getName();
  }

  public static function getStaticProperty($className, $propertyName)
  {

  }

  public static function invokeStaticMethod($className, $propertyName)
  {

  }

  public static function constructInstance($className, $arguments = array())
  {

  }

  public static function getInstanceProperty($instance, $propertyName)
  {

  }

  public static function invokeInstanceMethod($instance, $method, $arguments = array())
  {

  }

  public static function inspectStatic($argument)
  {
    $reflection = new ReflectionClass($argument);
  }

  public static function inspectInstance($instance, $variableName)
  {
    $class = new ReflectionClass($instance);
    $eol = "\n";
    $className = $class->getShortName();
    $doc = "$className $variableName$eol";
    if (self::hasProperty($class)) {
      $properties = array();
      foreach ($class->getProperties() as $property) {
        if (!self::getDocReader()->isIgnored($property))
        {
          $name = $property->getName();
          try {
            $value = $property->getValue($instance);
            if (is_null($value)) $value = "null";
            array_push($properties, "    .$name=$value;\n");
          }
          catch (\ReflectionException $ex) {
            //this occurs due to closures, no need to panic
          }
        }
      }
      $doc .= implode('', $properties);
    }
    return $doc;
  }
}
