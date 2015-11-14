<?php

namespace util;

require_once __DIR__.'/../../vendor/autoload.php';

use SuperClosure\Serializer;
use SuperClosure\Analyzer\TokenAnalyzer;
use SuperClosure\Analyzer\AstAnalyzer;


trait TDefine
{
  /**
   * @ignore
   **/
  // protected $definitions;
  //
  // protected $serializer;
  // protected $analyzer;

  /**
   * @ignore
   **/
  public function define($definitionFn) {
    // if (!isset($this->definitions))
    //   $this->definitions = array();
    // if (!isset($this->analyzer))
    //   $this->analyzer = new AstAnalyzer();
    // if (!isset($this->serializer))
    //   $this->serializer = new Serializer($this->analyzer);
    // array_push($this->definitions, $this->serializer->serialize($definitionFn));
    $definitionFn($this);
    return $this;
  }

  // public function replayDefinitions() {
  //   if (!isset($this->analyzer))
  //     $this->analyzer = new AstAnalyzer();
  //   if (!isset($this->serializer))
  //     $this->serializer = new Serializer($this->analyzer);
  //   foreach ($this->definitions as $definition) {
  //     $definitionFn = $this->serializer->unserialize($definition);
  //     $definitionFn($this);
  //   }
  // }
}
