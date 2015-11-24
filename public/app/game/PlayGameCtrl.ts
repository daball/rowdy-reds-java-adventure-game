module app.game {

  interface IGameInProgressModel {
    gameName: string;
    gameResource: ng.resource.IResourceClass<app.services.IPlayGameResource>;

    game: app.domain.IGameInProgress;

    selectedTab: string;

    commandLine: string;
    tabletCode: string;

    consoleHistoryEditor: any;
    commandLineEditor: any;
    tabletCodeEditor: any;

    commandHistoryAt: number;

    consoleHistoryAceOption: any;
    commandLineAceOption: any;
    tabletCodeAceOption: any;

    isLoading: boolean;
  }

  interface IPlayGameParams extends ng.route.IRouteParamsService {
    gameName: string;
  }

  class PlayGameCtrl implements IGameInProgressModel {
    gameName: string;
    gameResource: ng.resource.IResourceClass<app.services.IPlayGameResource>;

    game: app.domain.IGameInProgress;

    selectedTab: string;

    commandLine: string = "";
    tabletCode: string = "";

    consoleHistoryEditor: any;
    commandLineEditor: any;
    tabletCodeEditor: any;

    commandHistoryAt: number = 1;
    commandInProgress: string;

    consoleHistoryAceOption: any;
    commandLineAceOption: any;
    tabletCodeAceOption: any;

    isLoading: boolean = false;

    static $inject = ['$routeParams', 'PlayGameService', '$location', '$uibModal', '$window', '$timeout'];
    constructor(private $routeParams: IPlayGameParams,
                private gameService: app.services.PlayGameService,
                private $location: ng.ILocationService,
                private $uibModal: any,
                private $window: any,
                private $timeout: any) {
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

    playerHasEquipped(equipment, item) {
      if (equipment)
        for (var i = 0; i < equipment.length; i++)
          if (item == equipment[i])
            return true;
      return false;
    }

    showTabletCode() {
      this.selectedTab = "tabletCode";
      if (this.consoleHistoryEditor)
        this.consoleHistoryEditor.scrollToLine(this.consoleHistoryEditor.session.doc.getLength(), false, true);
      if (this.tabletCodeEditor)
        this.tabletCodeEditor.env.editor.focus();
    }

    showCommandLine() {
      this.selectedTab = "commandLine";
      if (this.consoleHistoryEditor)
        this.consoleHistoryEditor.scrollToLine(this.consoleHistoryEditor.session.doc.getLength(), false, true);
      if (this.commandLineEditor)
        this.commandLineEditor.env.editor.focus();
    }

    updateGame(game) {
      var scope = this;
      if (game.logger)
        for (var log in game.logger)
          console.log("Server logged:", game.logger[log]);
      this.game = game;
      this.commandHistoryAt = this.game.commandHistory.length;
      if (this.tabletCode != game.tabletCode)
        this.tabletCode = game.tabletCode;
      this.isLoading = false;
    }

    reconnectGame() {
      this.isLoading = true;
      this.gameResource.get({gameName: this.gameName}, (game: app.domain.IGameInProgress) => {
        // console.log(game);
        if (game.error)
          this.handleError(game.error);
        else
          this.updateGame(game);
        if (this.game.isExiting)
          this.$location.url("/game/" + this.game.gameName + "/thank-you");
      });
    }

    sendCommand(commandLine, tabletCode, callback) {
      // this.gameResource.
      this.isLoading = true;
      this.game.consoleHistory += "\n" + this.game.prompt + commandLine + "\nExecuting command on game service...";
      this.gameResource.save({gameName: this.gameName, commandLine: commandLine, tabletCode: tabletCode}, (game: app.domain.IGameInProgress) => {
        // console.log(game);
        if (game.error)
          this.handleError(game.error);
        else
          this.updateGame(game);
        if (callback)
          callback();
        if (this.game.isExiting)
          this.$location.url("/game/" + this.game.gameName + "/thank-you");
      });
    }

    handleError(error) {
      var modalInstance = this.$uibModal.open({
        animation: true,
        templateUrl: './views/modal-error.html',
        controller: 'ModalErrorCtrl',
        controllerAs: 'vm',
        size: 'lg',
        resolve: {
          error: () => {
            return error;
          }
        }
      });
      var scope = this;
      modalInstance.result.then(() => { scope.reconnectGame() }, () => { scope.reconnectGame() });
    }

    onConsoleHistoryLoaded(editor, scope: PlayGameCtrl) {
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
        getWidth: function(session, lastLineNumber, config) {
          return 3 * config.characterWidth;
        },
        getText: function(session, row) {
          //return row+1;
          var count: number = 0;
          for (var l: number = 0; l < session.doc.$lines.length && l < row; l++) {
            if (session.doc.$lines[l].indexOf('>') == 0)
              count++;
          }
          if (session.doc.$lines[row].indexOf('>') == 0)
            return (count+1).toString();
          return "";
        }
      };
    }

    onConsoleHistoryChanged(e, scope) {
      var editor = e[1];
      editor.scrollToLine(editor.session.doc.getLength(), false, true);
    }

    onCommandLineLoaded(editor, scope) {
      scope.commandLineEditor = editor;
      editor.on('blur', function () {
        if (scope.selectedTab == 'commandLine')
          scope.commandLineEditor.env.editor.focus();
        else if (scope.selectedTab == 'tabletCode')
          scope.tabletCodeEditor.env.editor.focus();
      });
      editor.session.gutterRenderer = {
        getWidth: function(session, lastLineNumber, config) {
          return 3 * config.characterWidth;
        },
        getText: function(session, row) {
          return ">";
        }
      };
      // editor.keyBinding.origOnTextInput = editor.keyBinding.onTextInput;
      // editor.keyBinding.onTextInput = function(text) {
      //   console.log("editor.keyBinding.onTextInput", arguments);
      //   this.origOnTextInput(text);
      // }
      editor.keyBinding.origOnCommandKey = editor.keyBinding.onCommandKey;
      editor.keyBinding.onCommandKey = function(e, hashId, keyCode) {
        var KEYCODES = { ENTER: 13, UP_ARROW: 38, DOWN_ARROW: 40,
                        LEFT_ARROW: 37, RIGHT_ARROW: 39, HOME: 36, END: 35
                      };
        if (scope.commandHistoryAt == scope.game.commandHistory.length) {
          //save the current command
          scope.commandInProgress = editor.getValue();
        }
        var loadCommandFromHistory = function () {
          if (scope.commandHistoryAt != scope.game.commandHistory.length)
            editor.session.setValue(scope.game.commandHistory[scope.commandHistoryAt]);
          else
            //recover the saved current command
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
      }
    }

    onCommandLineChanged(e, scope) {
      if (scope.commandLine.indexOf('\n') > -1 && !scope.commandLineReadOnly) {
        scope.commandLineReadOnly = true;
        let onCommandLineProcessed = () => {
          scope.commandLine = scope.commandLine.substring(scope.commandLine.indexOf('\n')+1);
          scope.commandLineReadOnly = false;
          //scope.onCommandLineChanged();
        };
        scope.sendCommand(scope.commandLine.substring(0, scope.commandLine.indexOf('\n')), scope.tabletCode, onCommandLineProcessed);
      }
    }

    onTabletCodeLoaded(editor, scope) {
      scope.tabletCodeEditor = editor;
      editor.on('blur', function () {
        if (scope.selectedTab == 'commandLine')
          scope.commandLineEditor.env.editor.focus();
        else if (scope.selectedTab == 'tabletCode')
          scope.tabletCodeEditor.env.editor.focus();
      });
      editor.session.gutterRenderer = {
        getWidth: function(session, lastLineNumber, config) {
          // console.log(config)
          return 3 * config.characterWidth;
        },
        getText: function(session, row) {
          return row+1;
        }
      };
    }

    onTabletCodeChanged(editor, scope) {
    }
}

  angular.module("RowdyRedApp").controller("PlayGameCtrl", PlayGameCtrl);

}
