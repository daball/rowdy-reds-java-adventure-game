var app;
(function (app) {
    var main = angular.module("RowdyRedApp", ["ngRoute", "ngResource", "ngSanitize", "ui.bootstrap", "ui.ace", "ng-showdown"]);
    main.config(routeConfig);
    routeConfig.$inject = ["$routeProvider"];
    function routeConfig($routeProvider) {
        $routeProvider
            .when('/docs/:docName', {
            templateUrl: "./views/view-manual-inline.html",
            controller: "UserManualCtrl as vm"
        })
            .otherwise("/docs/contents");
    }
})(app || (app = {}));
//# sourceMappingURL=manual-only.js.map