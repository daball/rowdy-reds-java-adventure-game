var app;
(function (app) {
    var game;
    (function (game) {
        var GamesListCtrl = (function () {
            function GamesListCtrl(gamesListService, $location, $uibModal) {
                this.gamesListService = gamesListService;
                this.$location = $location;
                this.$uibModal = $uibModal;
                this.isLoading = false;
                this.games = [];
                this.gamesListResource = gamesListService.getGames();
                this.loadGamesList();
            }
            GamesListCtrl.prototype.loadGamesList = function () {
                var _this = this;
                this.isLoading = true;
                this.gamesListResource.get(function (games) {
                    console.log(games);
                    if (games.error)
                        _this.handleError(games.error);
                    else {
                        _this.games = games.games;
                        _this.isLoading = false;
                    }
                });
            };
            GamesListCtrl.prototype.handleError = function (error) {
                if (error.xdebug_message)
                    error = error.xdebug_message;
                var modalInstance = this.$uibModal.open({
                    animation: true,
                    templateUrl: './views/modal-error.html',
                    controller: 'ModalErrorCtrl',
                    controllerAs: 'vm',
                    size: 'lg',
                    resolve: {
                        error: function () {
                            return error;
                        }
                    }
                });
                var scope = this;
                modalInstance.result.then(function () { scope.loadGamesList(); }, function () { scope.loadGamesList(); });
            };
            GamesListCtrl.prototype.playGame = function (gameName) {
                this.$location.url("/game/" + gameName + "/play");
            };
            GamesListCtrl.prototype.goHome = function () {
                this.$location.url("/");
            };
            GamesListCtrl.$inject = ['GamesListService', '$location', '$uibModal'];
            return GamesListCtrl;
        })();
        angular.module("RowdyRedApp").controller("GamesListCtrl", GamesListCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
