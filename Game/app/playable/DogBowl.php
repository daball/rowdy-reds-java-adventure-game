<?php

namespace playable;

require_once "GameObject.php";
require_once "BasicContainer.php";

class DogBowl extends BasicContainer // implements \Serializable
{
  protected $dog = null;

  public function __construct($name, $dog) {
    parent::__construct();
    $this->dog = $dog;
    $this->onSetItem(function ($itemName, $item) {
      if (is_a($item, 'Food')) {
        $dog = GameState::getGameState()->getPlayerRoom()->getItem($this->dog);
        $output = $dog->eat($item);
        if (GameState::getGameState()->getPlayerRoom()->directions->n->obstacleItem === $this->dog)
          GameState::getGameState()->getPlayerRoom()->directions->n->obstacleItem = null;
        else if (GameState::getGameState()->getPlayerRoom()->directions->s->obstacleItem === $this->dog)
          GameState::getGameState()->getPlayerRoom()->directions->s->obstacleItem = null;
        else if (GameState::getGameState()->getPlayerRoom()->directions->e->obstacleItem === $this->dog)
          GameState::getGameState()->getPlayerRoom()->directions->e->obstacleItem = null;
        else if (GameState::getGameState()->getPlayerRoom()->directions->w->obstacleItem === $this->dog)
          GameState::getGameState()->getPlayerRoom()->directions->w->obstacleItem = null;
        return $output;
      }
      else {
        return "You added the $itemName to the dog bowl, but nothing happened.";
      }
    });
  }

  /* ISerializable interface implementation */

  // public function serialize() {
  //   return serialize(
  //     array(
  //       'description' => $this->description,
  //       'items' => $this->items,
  //       'dog' => $this->dog,
  //     )
  //   );
  // }
  //
  // public function unserialize($data) {
  //   $data = unserialize($data);
  //   $this->dog = $data['dog'];
  //   $this->__construct($this->dog);
  //   $this->items = $data['items'];
  //   $this->description = $data['description'];
  // }
}
