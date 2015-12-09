var app;
(function (app) {
    var game;
    (function (game) {
        var ThankYouCtrl = (function () {
            function ThankYouCtrl($routeParams, gameService, $location, $uibModal) {
                this.$routeParams = $routeParams;
                this.gameService = gameService;
                this.$location = $location;
                this.$uibModal = $uibModal;
                this.gameName = $routeParams.gameName;
            }
            ThankYouCtrl.prototype.goHome = function () {
                this.$location.url("/");
            };
            ThankYouCtrl.$inject = ['$routeParams', 'PlayGameService', '$location', '$uibModal'];
            return ThankYouCtrl;
        })();
        angular.module("RowdyRedApp").controller("ThankYouCtrl", ThankYouCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
