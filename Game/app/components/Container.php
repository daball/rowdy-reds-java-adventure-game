<?php

namespace components;

require_once 'BaseComponent.php';

class Container extends BaseComponent
{
  //data
  protected $items = null;
  //data validation
  protected $maxItems = 10;
  protected $validItemTypes = null;
}
