var app;
(function (app) {
    var services;
    (function (services) {
        var PlayGameService = (function () {
            function PlayGameService($resource) {
                this.$resource = $resource;
            }
            PlayGameService.prototype.playGame = function () {
                return this.$resource("./api/play-game.php");
            };
            PlayGameService.prototype.consoleHistoryLoaded = function (consoleHistoryEditor) {
                consoleHistoryEditor.setReadOnly(true);
            };
            PlayGameService.$inject = ["$resource"];
            return PlayGameService;
        })();
        services.PlayGameService = PlayGameService;
        angular.module("RowdyRedApp").service("PlayGameService", PlayGameService);
    })(services = app.services || (app.services = {}));
})(app || (app = {}));
