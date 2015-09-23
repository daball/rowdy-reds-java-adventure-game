<?php

/**
 * A Key item must be used to open an Openable item.
 */
class Key
{
  /**
   * @ignore
   */
  private $keyID;

  public function __construct($keyID) {
    $this->keyID = $keyID;
  }
  
  public function getKeyID() (
    return $this->keyID;
  )
  
}
