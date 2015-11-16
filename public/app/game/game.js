var app;
(function (app) {
    var domain;
    (function (domain) {
        var GameInProgress = (function () {
            function GameInProgress(roomName, imageUrl, consoleHistory, commandHistory, eol, prompt, moves, isExiting) {
                this.roomName = roomName;
                this.imageUrl = imageUrl;
                this.consoleHistory = consoleHistory;
                this.commandHistory = commandHistory;
                this.eol = eol;
                this.prompt = prompt;
                this.moves = moves;
                this.isExiting = isExiting;
            }
            return GameInProgress;
        })();
        domain.GameInProgress = GameInProgress;
    })(domain = app.domain || (app.domain = {}));
})(app || (app = {}));
