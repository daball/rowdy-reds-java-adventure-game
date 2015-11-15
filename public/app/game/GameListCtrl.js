var app;
(function (app) {
    var game;
    (function (game) {
        var GameListCtrl = (function () {
            function GameListCtrl(gameService, $location) {
                var _this = this;
                this.gameService = gameService;
                this.$location = $location;
                this.games = [];
                var gamesResource = gameService.getGames();
                gamesResource.query(function (games) {
                    _this.games = games;
                });
            }
            GameListCtrl.prototype.startNewGame = function (gameName) {
                this.$location.url("/game/" + gameName + "/play");
            };
            GameListCtrl.$inject = ['GameListService', '$location'];
            return GameListCtrl;
        })();
        angular.module("RowdyRedApp").controller("GameListCtrl", GameListCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
