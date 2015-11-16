var app;
(function (app) {
    var game;
    (function (game_1) {
        var PlayGameCtrl = (function () {
            function PlayGameCtrl($routeParams, gameService, $location, $uibModal) {
                this.$routeParams = $routeParams;
                this.gameService = gameService;
                this.$location = $location;
                this.$uibModal = $uibModal;
                this.commandLine = "";
                this.commandLineReadOnly = false;
                this.gameName = $routeParams.gameName;
                this.game = {
                    imageUrl: "loading.png",
                    consoleHistory: "Connecting to game service...",
                    commandHistory: [],
                    eol: "\n",
                    prompt: "> ",
                    moves: 0,
                    isExiting: false
                };
                this.gameResource = gameService.playGame();
                this.reconnectGame();
            }
            PlayGameCtrl.prototype.reconnectGame = function () {
                var _this = this;
                this.gameResource.get({ gameName: this.gameName }, function (game) {
                    console.log(game);
                    if (game.commandHistory)
                        _this.game = game;
                    else if (game.error)
                        _this.handleError(game.error);
                });
            };
            PlayGameCtrl.prototype.sendCommand = function (command, callback) {
                var _this = this;
                this.game.consoleHistory += "\n" + this.game.prompt + command + "\nProcessing command via game service...";
                this.gameResource.save({ gameName: this.gameName, commandLine: command }, function (game) {
                    console.log(game);
                    if (game.commandHistory)
                        _this.game = game;
                    else if (game.error)
                        _this.handleError(game.error);
                    if (callback)
                        callback();
                });
            };
            PlayGameCtrl.prototype.handleError = function (error) {
                if (error.xdebug_message)
                    error = error.xdebug_message;
                var modalInstance = this.$uibModal.open({
                    animation: true,
                    templateUrl: './views/modal-error.html',
                    controller: 'ModalErrorCtrl',
                    controllerAs: 'vm',
                    resolve: {
                        error: function () {
                            return error;
                        }
                    }
                });
                modalInstance.result.then(this.reconnectGame, this.reconnectGame);
            };
            PlayGameCtrl.prototype.onConsoleHistoryLoaded = function (editor) {
                editor.on('focus', function () {
                    editor.blur();
                });
                editor.session.gutterRenderer = {
                    getWidth: function (session, lastLineNumber, config) {
                        return 3 * config.characterWidth;
                    },
                    getText: function (session, row) {
                        return row + 1;
                    }
                };
            };
            PlayGameCtrl.prototype.onConsoleHistoryChanged = function (e) {
                var editor = e[1];
                editor.scrollToLine(editor.session.doc.getLength(), false, true);
            };
            PlayGameCtrl.prototype.onCommandLineLoaded = function (editor) {
                editor.session.gutterRenderer = {
                    getWidth: function (session, lastLineNumber, config) {
                        return 3 * config.characterWidth;
                    },
                    getText: function (session, row) {
                        return ">";
                    }
                };
            };
            PlayGameCtrl.prototype.onCommandLineChanged = function () {
                var scope = this;
                if (scope.commandLine.indexOf('\n') > -1) {
                    console.log('onCommandLineChanged() hit');
                    scope.commandLineReadOnly = true;
                    var callback = function () {
                        scope.commandLine = scope.commandLine.substring(scope.commandLine.indexOf('\n') + 1);
                        scope.commandLineReadOnly = false;
                        scope.onCommandLineChanged();
                    };
                    scope.sendCommand(scope.commandLine.substring(0, scope.commandLine.indexOf('\n')), callback);
                }
            };
            PlayGameCtrl.$inject = ['$routeParams', 'PlayGameService', '$location', '$uibModal'];
            return PlayGameCtrl;
        })();
        angular.module("RowdyRedApp").controller("PlayGameCtrl", PlayGameCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
