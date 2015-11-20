var app;
(function (app) {
    var game;
    (function (game) {
        var GameListCtrl = (function () {
            function GameListCtrl(gameService, $location) {
                var _this = this;
                this.gameService = gameService;
                this.$location = $location;
                this.isLoading = false;
                this.games = [];
                var gamesResource = gameService.getGames();
                this.isLoading = true;
                gamesResource.query(function (games) {
                    _this.games = games;
                    _this.isLoading = false;
                });
            }
            GameListCtrl.prototype.playGame = function (gameName) {
                this.$location.url("/game/" + gameName + "/play");
            };
            GameListCtrl.prototype.goHome = function () {
                this.$location.url("/");
            };
            GameListCtrl.$inject = ['GameListService', '$location'];
            return GameListCtrl;
        })();
        angular.module("RowdyRedApp").controller("GameListCtrl", GameListCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
