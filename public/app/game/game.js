var app;
(function (app) {
    var domain;
    (function (domain) {
        var GameInProgress = (function () {
            function GameInProgress(gameName, roomName, imageUrl, consoleHistory, commandHistory, eol, prompt, moves, isExiting, error) {
                this.gameName = gameName;
                this.roomName = roomName;
                this.imageUrl = imageUrl;
                this.consoleHistory = consoleHistory;
                this.commandHistory = commandHistory;
                this.eol = eol;
                this.prompt = prompt;
                this.moves = moves;
                this.isExiting = isExiting;
                this.error = error;
            }
            return GameInProgress;
        })();
        domain.GameInProgress = GameInProgress;
        var GamesList = (function () {
            function GamesList(games, error) {
                this.games = games;
                this.error = error;
            }
            return GamesList;
        })();
        domain.GamesList = GamesList;
    })(domain = app.domain || (app.domain = {}));
})(app || (app = {}));
//# sourceMappingURL=game.js.map