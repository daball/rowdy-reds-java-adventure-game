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
    $serial = array();
    $class = new \ReflectionClass($this);
    do {
      $serial[$class->getName()] = array();
      $props = $class->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
      foreach ($props as $prop) {
        echo "Serialize hit " . $class->getName() . " property " . $prop->getName() . "<br />";
        if (!$prop->isStatic()) {
          $prop->setAccessible(true);
          if (is_a($prop->getValue($this), "\Closure"))
            $serial[$class->getName()][$prop->getName()] = new SerializableClosure($prop->getValue($this));
          else if (is_array($prop->getValue($this))) {
            $serial[$class->getName()][$prop->getName()] = array();
            foreach ($prop->getValue($this) as $key => $value) {
              if (is_a($prop->getValue($this), "\Closure"))
                $serial[$class->getName()][$prop->getName()][$key] = new SerializableClosure($value);
              else
                $serial[$class->getName()][$prop->getName()][$key] = serialize($value);
            }
          }
          else
            //$serial[$class->getName()][$prop->getName()] = $prop->getValue($this);
            $serial[$class->getName()][$prop->getName()] = serialize($prop->getValue($this));
        }
      }
    } while ($class = $class->getParentClass());
    echo serialize($serial) . "<br /><br />";
    return serialize($serial);
  }

  /**
   * @ignore
   */
  private function serializeParent() {
    return parent::serialize();
  }

  /**
   * @ignore
   */
  public function unserialize($data) {
    echo "Incoming unserialize() with $data<br /><br />";
    $data = unserialize($data);
    $class = new \ReflectionClass($this);
    do {
      $props = $class->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
      foreach ($props as $prop) {
        $prop->setAccessible(true);
        echo "Unserialize hit " . $class->getName() . " property " . $prop->getName() . " with value " . $data[$class->getName()][$prop->getName()] . "<br />";
        if (array_key_exists($prop->getName(), $data[$class->getName()])) {
          $prop->setValue($this, unserialize($data[$class->getName()][$prop->getName()]));
        }
      }
    } while ($class = $class->getParentClass());
    // $parent = $class->getParentClass();
    // echo $class->getName() . " parent = ". get_parent_class($this) . "<br />";
    // if (get_parent_class($this) !== FALSE && $parent->hasMethod('unserialize')) {
      // echo "Object " . $class->getName() . " has serializable parent " . $parent->getName() . "<br />";
      //$parent->getMethod('unserialize')->invoke($data[$parent->getName()]);
      //$data[$parent->getName()] = $parent->getMethod('unserialize')->invoke($this);
      //$serial[$parent->getName()] = $value;
      //$this->unserializeParent($data[$parent->getName()]);
    // }
  }

  /**
   * @ignore
   */
  private function unserializeParent($data) {
    parent::unserialize($data);
  }
}
