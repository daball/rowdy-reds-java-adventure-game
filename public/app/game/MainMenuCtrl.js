var app;
(function (app) {
    var game;
    (function (game) {
        var MainMenuCtrl = (function () {
            function MainMenuCtrl($location) {
                this.$location = $location;
            }
            MainMenuCtrl.prototype.startNewGame = function () {
                this.$location.url("/games");
            };
            MainMenuCtrl.$inject = ["$location"];
            return MainMenuCtrl;
        })();
        angular.module("RowdyRedApp").controller("MainMenuCtrl", MainMenuCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
