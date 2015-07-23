angular.module("AppRouterModule", ["ui.router", "GameUIControllerModule"])
  .config(function ($stateProvider, $urlRouterProvider, HomeUIController, GameUIController) {
    $urlRouterProvider.otherwise("/");

    $stateProvider
      .state("home", {
        url: "/home",
        views: {
          main: { template: "./views/home.html" }
        }
        controller:
      })
      .state("play", {
        url: "/play",
        views: {
          main: { template: "./views/play.html" }
        }
        controller: GameUIController
      })
  })
