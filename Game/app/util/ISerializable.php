<?php

namespace util;

interface ISerializable {

  /**
   * Serializes an ISerializable to a String.
   * @return String
   **/
  public function serialize();

  /**
   * Unserializes an ISerializable from a String.
   * @param String data
   **/
  public function unserialize($data);
}
