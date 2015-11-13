<?php
namespace playable;

require_once 'GameObject.php';
require_once 'Door.php';
require_once 'Key.php';
require_once 'LockedDoor.php';

$door = new Door('north');
echo "> north\n";
echo $door->getComponent('Collider')->collide() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> door.open();\n";
echo $door->getComponent('Openable')->open() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> north\n";
$collision = $door->getComponent('Collider')->collide();
echo $collision?"You did not pass through.":"You passed through." . "\n";
echo "> door.close();\n";
echo $door->getComponent('Openable')->close() . "\n";


$key = new Key('funkyKey');
$door = new LockedDoor('south', $key);
echo "> south\n";
echo $door->getComponent('Collider')->collide() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> door.open();\n";
echo $door->getComponent('Openable')->open() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> door.unlock(leftHand);\n";
echo $door->getComponent('Lockable')->unlock($key) . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> south\n";
$collision = $door->getComponent('Collider')->collide();
echo ($collision?"You did not pass through.":"You passed through.") . "\n";
echo "> door.close();\n";
echo $door->getComponent('Openable')->close() . "\n";
echo "> inspect door\n";
echo $door->getComponent('Inspector')->inspect() . "\n";
echo "> door.open();\n";
echo $door->getComponent('Openable')->open() . "\n";
echo "> door.lock(leftHand);\n";
echo $door->getComponent('Lockable')->lock($key) . "\n";
