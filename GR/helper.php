<?php
  if(!isset($_SESSION)){
    session_start();
  }

  include 'functions.php';

  if (!defined('HOST_STUNNEL')){
    define('HOST_STUNNEL', '127.0.0.1');
    define('DB_HOST', HOST_STUNNEL);
    define('DB_USER', 'codecombatuser');
    define('DB_PASS', 'Goo1mei2Tho9ahze');
    define('DB_NAME', 'codecombat');
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  if(isset($_SESSION['Hold'])){
    saveState();
  }
  else if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    if($id == 1){
      $_SESSION['lastCommand'] = "";
      $_SESSION['console'] = "";
      $_SESSION['Hold'] = "YES";
      loadArrays();
      startNewGame();
      saveState();

    }
    else if($id == 2){
      // Try to load the game if no game in cookies display error
    }
    else if($id == 3){
      // Redirect To Options Screen
    }
    else{
      header("Location: index.php");
      exit;
    }
  }
  else
  {
    header("Location: index.php");
    exit;
  }

  function loadArrays(){
    buildArray('Room', 'Description', 'room_descriptions', 'roomDescriptions');
    buildArray('Room', 'Image', 'room_images', 'roomImage');
    buildArray('Room', 'Connection', 'room_connections', 'roomConnections');
    buildArray('Object', 'Description', 'object_descriptions', 'objectDescriptions');
    buildArray('Room', 'Object', 'room_objects', 'roomObjects');
    buildArray('Item', 'HasItem', 'users_items', 'usersItems');
    buildArray('Hand', 'Item', 'hands_array', 'handsArray');
    buildArray('Space', 'Item', 'back_pack_array', 'backPackArray');
    buildArray('Item', 'Image', 'new_images', 'newImages');
    buildArray('Command', 'Function', 'commands_array', 'commandsArray');
    buildArray('Command', 'Definition', 'definition_array', 'definition');
    buildArray('Room', 'Obstacle', 'obstacles_array', 'obstacles');
    buildArray('variable', 'assign', 'variable_objects', 'vObjects');
    buildArray('room', 'text', 'tabtext', 'tabText');
  }

  function buildArray($col1, $col2, $table, $arrayName){
    if(!isset($_SESSION[$arrayName])){
      global $db;
      $_SESSION[$arrayName] = array();
      $sql = "SELECT " . $col1 . ", " . $col2 . " FROM " . $table . ";";
      $result = $db->query($sql);

      while($row = $result->fetch_assoc()){
        $_SESSION[$arrayName][$row[$col1]] = $row[$col2];
      }
    }
  }
?>