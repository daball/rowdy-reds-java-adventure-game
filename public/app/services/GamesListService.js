var app;
(function (app) {
    var services;
    (function (services) {
        var GamesListService = (function () {
            function GamesListService($resource) {
                this.$resource = $resource;
            }
            GamesListService.prototype.getGames = function () {
                return this.$resource("./api/list-games.php");
            };
            GamesListService.$inject = ["$resource"];
            return GamesListService;
        })();
        services.GamesListService = GamesListService;
        angular.module("RowdyRedApp").service("GamesListService", GamesListService);
    })(services = app.services || (app.services = {}));
})(app || (app = {}));
