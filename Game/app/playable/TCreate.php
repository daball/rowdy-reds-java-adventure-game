<?php

namespace playable;

trait TCreate
{
  public static function create()
  {
    return new self();
  }
}
