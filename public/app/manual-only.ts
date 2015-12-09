module app {
  var main = angular.module("RowdyRedApp", ["ngRoute", "ngResource", "ngSanitize", "ui.bootstrap", "ui.ace", "ng-showdown" ]);

  main.config(routeConfig);

  routeConfig.$inject = ["$routeProvider"];
  function routeConfig($routeProvider: ng.route.IRouteProvider): void {
    $routeProvider
      .when('/docs/:docName', {
        templateUrl: "./views/view-manual-inline.html",
        controller: "UserManualCtrl as vm"
      })
      .otherwise("/docs/contents");
  }
}
