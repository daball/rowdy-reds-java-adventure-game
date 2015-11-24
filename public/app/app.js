var app;
(function (app) {
    var main = angular.module("RowdyRedApp", ["ngRoute", "ngResource", "ngSanitize", "ui.bootstrap", "ui.ace", "ng-showdown"]);
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
            controller: "GamesListCtrl as vm"
        })
            .when('/game/:gameName/play', {
            templateUrl: "./views/play-game.html",
            controller: "PlayGameCtrl as vm"
        })
            .when('/game/:gameName/thank-you', {
            templateUrl: "./views/thank-you.html",
            controller: "ThankYouCtrl as vm"
        })
            .when('/docs/:docName', {
            templateUrl: "./views/view-manual.html",
            controller: "UserManualCtrl as vm"
        })
            .otherwise("/");
    }
})(app || (app = {}));
//# sourceMappingURL=app.js.map