angular.module('GameConfigurationModule', [])
  .value('debug', true) //set this to true to enable debug features, false to disable
  .value('appName', "Rowdy Red\'s Java Adventure")
  .value('userServiceAPIUrl', "http://localhost/user-api.php")
  .value('promptDisplay', '> ')
  .value('promptSecretChar', '\u25CF')
