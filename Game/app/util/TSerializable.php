<?php

namespace util;

require_once __DIR__.'/../../vendor/autoload.php';

use \ReflectionClass;
use \ReflectionProperty;
use \Opis\Closure\SerializableClosure;

trait TSerializable
{
  /**
   * @ignore
   */
  public function serialize() {
    $reflect = new \ReflectionClass($this);
    $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
    $serial = array();
    foreach ($props as $prop) {
      if (!$prop->isStatic()) {
        $prop->setAccessible(true);
        $value = $prop->getValue($this);
        if (is_a($prop->getValue($this), "\Closure"))
          $value = new SerializableClosure($value);
        $serial[$prop->getName()] = $value;
      }
    }
    return serialize($serial);
  }

  /**
   * @ignore
   */
  public function unserialize($data) {
    $data = unserialize($data);
    $reflect = new \ReflectionClass($this);
    $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
    foreach ($props as $prop) {
      $prop->setAccessible(true);
      if (array_key_exists($prop->getName(), $data)) {
        $prop->setValue($this, $data[$prop->getName()]);
      }
    }
  }
}
