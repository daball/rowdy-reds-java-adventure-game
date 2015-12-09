<?php

namespace component\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/components/Equippable.php';
require_once __DIR__.'/../../../../app/game/GameObject.php';
require_once __DIR__.'/../../../../app/game/Player.php';

use \components\Equippable;
use \game\GameObject;
use \game\Player;

class EquippableTest extends \PHPUnit_Framework_TestCase
{
  public function testEquippable()
  {
    $equipment1 = (new GameObject("equipment1"))->define(function ($equipment) {
      $equipment->addComponent(new Equippable());
    });
    $equipment2 = (new GameObject("equipment2"))->define(function ($equipment) {
      $equipment->addComponent(new Equippable());
    });
    $equipment3 = (new GameObject("equipment3"))->define(function ($equipment) {
      $equipment->addComponent(new Equippable());
    });
    $player = new Player();

    $this->assertFalse($equipment1->getComponent('Equippable')->isEquipped());
    $this->assertFalse($equipment2->getComponent('Equippable')->isEquipped());
    $this->assertFalse($equipment3->getComponent('Equippable')->isEquipped());

    $player->equipItem($equipment1);
    $player->equipItem($equipment2);
    $player->equipItem($equipment3);

    $this->assertTrue($equipment1->getComponent('Equippable')->isEquipped());
    $this->assertTrue($equipment2->getComponent('Equippable')->isEquipped());
    $this->assertTrue($equipment3->getComponent('Equippable')->isEquipped());

    $this->assertTrue($player->hasEquipmentItem('equipment1'));
    $this->assertTrue($player->hasEquipmentItem('equipment2'));
    $this->assertTrue($player->hasEquipmentItem('equipment3'));

    $this->assertEquals($equipment1->getName(), $player->getEquipmentItem('equipment1')->getName());
    $this->assertEquals($equipment2->getName(), $player->getEquipmentItem('equipment2')->getName());
    $this->assertEquals($equipment3->getName(), $player->getEquipmentItem('equipment3')->getName());

    $this->assertTrue($player->getEquipmentItem('equipment1')->getComponent('Equippable')->isEquipped());
    $this->assertTrue($player->getEquipmentItem('equipment2')->getComponent('Equippable')->isEquipped());
    $this->assertTrue($player->getEquipmentItem('equipment3')->getComponent('Equippable')->isEquipped());
  }
}
