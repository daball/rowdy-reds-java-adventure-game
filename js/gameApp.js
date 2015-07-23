angular.module('gameApp', ['ngRoute', 'gameApp.views.home', 'gameApp.views.game'])
  .config(['$routeProvider', function($routeProvider) {
    $routeProvider.otherwise({redirectTo: '/game'});
  }]);
