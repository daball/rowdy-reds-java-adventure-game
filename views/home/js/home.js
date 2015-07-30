angular.module('gameApp.views.home', ['ngRoute', 'gameApp.gameConfig', 'gameApp.userService'])
  .config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/home', {
      templateUrl: './views/home/home.html',
      controller: 'HomeController'
    });
  }])
  .controller('HomeController', function($scope, $gameConfig, $userService) {
    $rootScope.$gameConfig = $gameConfig;
  });
