angular.module('gameApp.gameConfig', [])
  .value('$gameConfig', {
    debug: true, //set this to true to enable debug features, false to disable
    appName: "Rowdy Red\'s Java Adventure",
    gameStateServiceAPIUrl: "http://localhost/rowdyred/api/game.php",
    userServiceAPIUrl: "Rowdy Red\'s Java Adventure",
    promptDisplay: "> "
  })
