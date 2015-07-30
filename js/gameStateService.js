angular.module('gameApp.gameStateService', ['gameApp.gameConfig'])
  //allows developer to programatically generate maps
  .service("$gameStateService", function($gameConfig, $http) {
    var svc = this;    
    svc.getGameState = function getGameState(callback) {
      $http.get($gameConfig.gameStateServiceAPIUrl)
        .success(function(data, status, headers, config) {
          // this callback will be called asynchronously
          // when the response is available
          callback(false, data, status, headers, config);
        })
        .error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          callback(false, data, status, headers, config);
        })
    };
    svc.postCommand = function postCommand(commandLine, callback) {
      $http.post($gameConfig.gameStateServiceAPIUrl, { 'commandLine': commandLine })
        .success(function(data, status, headers, config) {
          // this callback will be called asynchronously
          // when the response is available
          callback(false, data, status, headers, config);
        })
        .error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          callback(false, data, status, headers, config);
        })
    };
  });
