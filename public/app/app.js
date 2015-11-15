var app;
(function (app) {
    var main = angular.module("RowdyRedApp", ["ngRoute", "ngResource"]);
    main.config(routeConfig);
    routeConfig.$inject = ["$routeProvider"];
    function routeConfig($routeProvider) {
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
            controller: "PlayCtrl as vm"
        })
            .otherwise("/");
    }
})(app || (app = {}));
