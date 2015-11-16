var app;
(function (app) {
    var main = angular.module("RowdyRedApp", ["ngRoute", "ngResource", "ngSanitize", "ui.bootstrap", "ui.ace"]);
    main.config(routeConfig);
    routeConfig.$inject = ["$routeProvider"];
    function routeConfig($routeProvider) {
        $routeProvider
            .when("/", {
            templateUrl: "./views/main-menu.html",
            controller: "MainMenuCtrl as vm"
        })
            .when("/games", {
            templateUrl: "./views/list-games.html",
            controller: "GameListCtrl as vm"
        })
            .when('/game/:gameName/play', {
            templateUrl: "./views/play-game.html",
            controller: "PlayGameCtrl as vm"
        })
            .otherwise("/");
    }
})(app || (app = {}));
