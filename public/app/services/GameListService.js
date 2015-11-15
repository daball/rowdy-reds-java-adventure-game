var app;
(function (app) {
    var services;
    (function (services) {
        var GameListService = (function () {
            function GameListService($resource) {
                this.$resource = $resource;
            }
            GameListService.prototype.getGames = function () {
                return this.$resource("./api/list-games.php");
            };
            GameListService.$inject = ["$resource"];
            return GameListService;
        })();
        services.GameListService = GameListService;
        angular.module("RowdyRedApp").service("GameListService", GameListService);
    })(services = app.services || (app.services = {}));
})(app || (app = {}));
