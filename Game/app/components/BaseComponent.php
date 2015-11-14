<?php

namespace components;

require_once __DIR__.'/../util/TDefine.php';
require_once __DIR__.'/../util/TSerializable.php';
require_once __DIR__.'/../../vendor/autoload.php';

use util\TDefine;
use util\TSerializable;
use SuperClosure\Serializer;

/**
 * @ignore
 */
abstract class BaseComponent implements \Serializable
{
  use TDefine;
  use TSerializable;

  /**
   * @ignore
   */
  private $parent;

  /**
   * @ignore
   */
  public function getParent() {
    return $this->parent;
  }

  /**
   * @ignore
   */
  public function setParent($parent) {
    //assert $parent is the right type
    if (!is_a($parent, "\playable\GameObject")) {
      throw new \Exception('You must pass a \playable\GameObject instance or an object from a class extended from \playable\GameObject in order to call \components\BaseComponent\setParent().');
    }
    $this->parent = $parent;
    return $this->getParent();
  }

  // /**
  //  * @ignore
  //  */
  // public function serialize() {
  //   return serialize(
  //     array(
  //       'parent' => $this->parent,
  //       // 'definitions' => $this->definitions,
  //     )
  //   );
  // }
  //
  // /**
  //  * @ignore
  //  */
  // public function unserialize($data) {
  //   $data = unserialize($data);
  //   $this->parent = $data['parent'];
  //   // $this->definitions = $data['definitions'];
  //   // //replay definitions
  //   // $this->replayDefinitions();
  // }
}
