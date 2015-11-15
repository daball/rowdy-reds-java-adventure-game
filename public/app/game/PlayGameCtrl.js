var app;
(function (app) {
    var game;
    (function (game_1) {
        var PlayGameCtrl = (function () {
            function PlayGameCtrl($routeParams, gameService, $location) {
                var _this = this;
                this.$routeParams = $routeParams;
                this.gameService = gameService;
                this.$location = $location;
                this.gameName = $routeParams.gameName;
                this.gameResource = gameService.playGame();
                this.gameResource.get({ gameName: this.gameName }, function (game) {
                    _this.game = game;
                });
            }
            PlayGameCtrl.prototype.sendCommand = function (commandLine) {
                var _this = this;
                this.gameResource.post({ gameName: this.gameName, commandLine: commandLine }, function (game) {
                    _this.game = game;
                });
            };
            PlayGameCtrl.$inject = ['$routeParams', 'PlayGameService', '$location'];
            return PlayGameCtrl;
        })();
        angular.module("RowdyRedApp").controller("PlayGameCtrl", PlayGameCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
