<?php

namespace util;

require_once __DIR__.'/../../vendor/autoload.php';

use \Opis\Closure\SerializableClosure;

trait TSerializableClosure
{
  /**
   * @ignore
   */
  protected function serializableClosure($closureFn) {
    return new SerializableClosure($closureFn);
  }
}
