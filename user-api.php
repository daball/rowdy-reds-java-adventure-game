<?php

//Gather JSON request
$json = json_decode(file_get_contents('php://input'), true);
$req = json_decode($json);

//Logs user in or returns error
function login($req) {
  $res = "";
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //this implementation is error-only, insert a database for something real
  $res = json_encode(array('ok'=>false, 'err'=>'User name already exists.'));
}

//Logs user in or returns error
function login($req) {
  $res = "";
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //this implementation only uses data fixtures, insert a database for something real
  if ($req->{'userName'} == "testuser" && $req->{'password'} == "testpass") {
    //login ok
    $res = json_encode(array('ok'=>true, 'msg'=>"Logged in.", 'userName'=>$req->{'userName'}));

    //issue a session cookie
    if (session_start()) {
      $_SESSION["userName"] = $req->{'userName'};
      $_SESSION["loggedIn"] = true;
    }
  }
  else {
    //login not ok, let's say username and password are not correct
    $res = json_encode(array('ok'=>false, 'userName'=>$req->{'userName'}, 'err'=>"Invalid user name or password, according to the story inside PHP."));
  }
  return $res;
}

//Logs user out
function logout($req) {
  if (session_status() === PHP_SESSION_ACTIVE) session_destroy();
  $res = json_encode(array('ok'=>false, 'msg'=>"Logged out."));
}

//Login status
function status($req) {
  if (session_status() === PHP_SESSION_ACTIVE && $_SESSION["loggedIn"])
    $res = json_encode(array('ok'=>true, 'userName'=>$_SESSION['userName'], 'msg'=>>"User is logged in."))
  else
    $res = json_encode(array('ok'=>false, 'msg'=>>"Session not logged in or session has expired. Please login."))
}

//Saves current game state
function saveGameState($req) {
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //this implementation will use session variables for data storage, for something real, use a database
  if (session_status() === PHP_SESSION_ACTIVE && $_SESSION["loggedIn"]) {
    $_SESSION["gameState"] = json_encode($req->{'gameState'});
    $res = json_encode(array('ok'=>true, 'userName'=>$_SESSION['userName'], 'msg'=>>"User is logged in."))
  }
  else
    $res = json_encode(array('ok'=>false, 'msg'=>>"Session not logged in or session has expired. Please login in order to save game state."))
}

//Retrieve game state
function retrieveGameState($req) {
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //pretend you're doing something with a database
  //this implementation will use session variables for data storage, for something real, use a database
  if (session_status() === PHP_SESSION_ACTIVE && $_SESSION['loggedIn']) {
    if ($_SESSION['gameState'])
      $res = json_encode(array('ok'=>true, 'userName'=>$_SESSION['userName'], 'gameState'=>>json_decode($_SESSION['gameState'])));
    else
      //TODO:change error for database store instead of session store
      $res = json_encode(array('ok'=>false, 'msg'=>>"No game state was saved in the this user session."));
  }
  else
    $res = json_encode(array('ok'=>false, 'msg'=>>"Session not logged in or session has expired. Please login in order to save game state."))
}

//Dispatches correct command based on {action:'action'}
function dispatch($req) {
  switch ($req->{'action'}) {
    case "register":
      return register($req);
    case "login":
      return login($req);
    case "logout":
      return logout($req);
    case "status":
      return status($req);
    case "saveGameState":
      return saveGameState($req);
    case "openGameState":
      return openGameState($req);
  }
}

//Dispatch correct command and return output
echo dispatch($req);
?>
