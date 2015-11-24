var app;
(function (app) {
    var game;
    (function (game_1) {
        var PlayGameCtrl = (function () {
            function PlayGameCtrl($routeParams, gameService, $location, $uibModal, $window, $timeout) {
                this.$routeParams = $routeParams;
                this.gameService = gameService;
                this.$location = $location;
                this.$uibModal = $uibModal;
                this.$window = $window;
                this.$timeout = $timeout;
                this.commandLine = "";
                this.tabletCode = "";
                this.commandHistoryAt = 1;
                this.isLoading = false;
                var scope = this;
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
                this.showCommandLine();
                this.reconnectGame();
                this.consoleHistoryAceOption = {
                    useWrapMode: true,
                    showGutter: true,
                    theme: 'twilight',
                    mode: 'text',
                    onLoad: function (_ace) {
                        return scope.onConsoleHistoryLoaded(_ace, scope);
                    },
                    onChange: function (_ace) {
                        return scope.onConsoleHistoryChanged(_ace, scope);
                    }
                };
                this.commandLineAceOption = {
                    useWrapMode: true,
                    showGutter: true,
                    theme: 'twilight',
                    mode: 'text',
                    onLoad: function (_ace) {
                        return scope.onCommandLineLoaded(_ace, scope);
                    },
                    onChange: function (_ace) {
                        return scope.onCommandLineChanged(_ace, scope);
                    }
                };
                this.tabletCodeAceOption = {
                    useWrapMode: true,
                    showGutter: true,
                    theme: 'solarized_light',
                    mode: 'java',
                    onLoad: function (_ace) {
                        return scope.onTabletCodeLoaded(_ace, scope);
                    },
                    onChange: function (_ace) {
                        return scope.onTabletCodeChanged(_ace, scope);
                    }
                };
            }
            PlayGameCtrl.prototype.playerHasEquipped = function (equipment, item) {
                if (equipment)
                    for (var i = 0; i < equipment.length; i++)
                        if (item == equipment[i])
                            return true;
                return false;
            };
            PlayGameCtrl.prototype.showTabletCode = function () {
                this.selectedTab = "tabletCode";
                if (this.consoleHistoryEditor)
                    this.consoleHistoryEditor.scrollToLine(this.consoleHistoryEditor.session.doc.getLength(), false, true);
                if (this.tabletCodeEditor)
                    this.tabletCodeEditor.env.editor.focus();
            };
            PlayGameCtrl.prototype.showCommandLine = function () {
                this.selectedTab = "commandLine";
                if (this.consoleHistoryEditor)
                    this.consoleHistoryEditor.scrollToLine(this.consoleHistoryEditor.session.doc.getLength(), false, true);
                if (this.commandLineEditor)
                    this.commandLineEditor.env.editor.focus();
            };
            PlayGameCtrl.prototype.updateGame = function (game) {
                var scope = this;
                if (game.logger)
                    for (var log in game.logger)
                        console.log("Server logged:", game.logger[log]);
                this.game = game;
                this.commandHistoryAt = this.game.commandHistory.length;
                if (this.tabletCode != game.tabletCode)
                    this.tabletCode = game.tabletCode;
                this.isLoading = false;
            };
            PlayGameCtrl.prototype.reconnectGame = function () {
                var _this = this;
                this.isLoading = true;
                this.gameResource.get({ gameName: this.gameName }, function (game) {
                    if (game.error)
                        _this.handleError(game.error);
                    else
                        _this.updateGame(game);
                    if (_this.game.isExiting)
                        _this.$location.url("/game/" + _this.game.gameName + "/thank-you");
                });
            };
            PlayGameCtrl.prototype.sendCommand = function (commandLine, tabletCode, callback) {
                var _this = this;
                this.isLoading = true;
                this.game.consoleHistory += "\n" + this.game.prompt + commandLine + "\nExecuting command on game service...";
                this.gameResource.save({ gameName: this.gameName, commandLine: commandLine, tabletCode: tabletCode }, function (game) {
                    if (game.error)
                        _this.handleError(game.error);
                    else
                        _this.updateGame(game);
                    if (callback)
                        callback();
                    if (_this.game.isExiting)
                        _this.$location.url("/game/" + _this.game.gameName + "/thank-you");
                });
            };
            PlayGameCtrl.prototype.handleError = function (error) {
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
                modalInstance.result.then(function () { scope.reconnectGame(); }, function () { scope.reconnectGame(); });
            };
            PlayGameCtrl.prototype.onConsoleHistoryLoaded = function (editor, scope) {
                scope.consoleHistoryEditor = editor;
                editor.on('focus', function () {
                    if (scope.selectedTab == 'commandLine')
                        scope.commandLineEditor.env.editor.focus();
                    else if (scope.selectedTab == 'tabletCode')
                        scope.tabletCodeEditor.env.editor.focus();
                });
                editor.setHighlightActiveLine(false);
                scope.$window.$(function () {
                    scope.$timeout(function () {
                        editor.env.editor.focus();
                    }, 50);
                });
                editor.session.gutterRenderer = {
                    getWidth: function (session, lastLineNumber, config) {
                        return 3 * config.characterWidth;
                    },
                    getText: function (session, row) {
                        var count = 0;
                        for (var l = 0; l < session.doc.$lines.length && l < row; l++) {
                            if (session.doc.$lines[l].indexOf('>') == 0)
                                count++;
                        }
                        if (session.doc.$lines[row].indexOf('>') == 0)
                            return (count + 1).toString();
                        return "";
                    }
                };
            };
            PlayGameCtrl.prototype.onConsoleHistoryChanged = function (e, scope) {
                var editor = e[1];
                editor.scrollToLine(editor.session.doc.getLength(), false, true);
            };
            PlayGameCtrl.prototype.onCommandLineLoaded = function (editor, scope) {
                scope.commandLineEditor = editor;
                editor.on('blur', function () {
                    if (scope.selectedTab == 'commandLine')
                        scope.commandLineEditor.env.editor.focus();
                    else if (scope.selectedTab == 'tabletCode')
                        scope.tabletCodeEditor.env.editor.focus();
                });
                editor.session.gutterRenderer = {
                    getWidth: function (session, lastLineNumber, config) {
                        return 3 * config.characterWidth;
                    },
                    getText: function (session, row) {
                        return ">";
                    }
                };
                editor.keyBinding.origOnCommandKey = editor.keyBinding.onCommandKey;
                editor.keyBinding.onCommandKey = function (e, hashId, keyCode) {
                    var KEYCODES = { ENTER: 13, UP_ARROW: 38, DOWN_ARROW: 40,
                        LEFT_ARROW: 37, RIGHT_ARROW: 39, HOME: 36, END: 35
                    };
                    if (scope.commandHistoryAt == scope.game.commandHistory.length) {
                        scope.commandInProgress = editor.getValue();
                    }
                    var loadCommandFromHistory = function () {
                        if (scope.commandHistoryAt != scope.game.commandHistory.length)
                            editor.session.setValue(scope.game.commandHistory[scope.commandHistoryAt]);
                        else
                            editor.session.setValue(scope.commandInProgress);
                        var row = 0;
                        var column = editor.session.getLine(row).length;
                        editor.gotoLine(row + 1, column);
                    };
                    switch (keyCode) {
                        case KEYCODES.ENTER:
                            var row = 0;
                            var column = editor.session.getLine(row).length;
                            editor.gotoLine(row + 1, column);
                            editor.keyBinding.origOnCommandKey(e, hashId, keyCode);
                            break;
                        case KEYCODES.UP_ARROW:
                            if (--scope.commandHistoryAt < 0)
                                scope.commandHistoryAt = scope.game.commandHistory.length;
                            loadCommandFromHistory();
                            break;
                        case KEYCODES.DOWN_ARROW:
                            if (++scope.commandHistoryAt > scope.game.commandHistory.length)
                                scope.commandHistoryAt = 0;
                            loadCommandFromHistory();
                            break;
                        default:
                            editor.keyBinding.origOnCommandKey(e, hashId, keyCode);
                            break;
                    }
                };
            };
            PlayGameCtrl.prototype.onCommandLineChanged = function (e, scope) {
                if (scope.commandLine.indexOf('\n') > -1 && !scope.commandLineReadOnly) {
                    scope.commandLineReadOnly = true;
                    var onCommandLineProcessed = function () {
                        scope.commandLine = scope.commandLine.substring(scope.commandLine.indexOf('\n') + 1);
                        scope.commandLineReadOnly = false;
                    };
                    scope.sendCommand(scope.commandLine.substring(0, scope.commandLine.indexOf('\n')), scope.tabletCode, onCommandLineProcessed);
                }
            };
            PlayGameCtrl.prototype.onTabletCodeLoaded = function (editor, scope) {
                scope.tabletCodeEditor = editor;
                editor.on('blur', function () {
                    if (scope.selectedTab == 'commandLine')
                        scope.commandLineEditor.env.editor.focus();
                    else if (scope.selectedTab == 'tabletCode')
                        scope.tabletCodeEditor.env.editor.focus();
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
            PlayGameCtrl.prototype.onTabletCodeChanged = function (editor, scope) {
            };
            PlayGameCtrl.$inject = ['$routeParams', 'PlayGameService', '$location', '$uibModal', '$window', '$timeout'];
            return PlayGameCtrl;
        })();
        angular.module("RowdyRedApp").controller("PlayGameCtrl", PlayGameCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
//# sourceMappingURL=PlayGameCtrl.js.map