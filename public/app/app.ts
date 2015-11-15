module app {
  var main = angular.module("RowdyRedApp", ["ngRoute", "ngResource", "ui.ace" /*, "common.services", "productResourceMock"*/ ]);

  main.config(routeConfig);

  routeConfig.$inject = ["$routeProvider"];
  function routeConfig($routeProvider: ng.route.IRouteProvider): void {
    $routeProvider
      .when("/", {
        templateUrl: "./partials/main-menu.html",
        controller: "MainMenuCtrl as vm"
      })
      .when("/games", {
        templateUrl: "./partials/list-games.html",
        controller: "GameListCtrl as vm"
      })
      .when('/game/:gameName/play', {
        templateUrl: "./partials/play-game.html",
        controller: "PlayGameCtrl as vm"
      })
      .otherwise("/");
  }
}
