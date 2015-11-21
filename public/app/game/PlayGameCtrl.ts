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

    consoleHistoryAceOption: any;
    commandLineAceOption: any;
    tabletCodeAceOption: any;

    isLoading: boolean = false;

    static $inject = ['$routeParams', 'PlayGameService', '$location', '$uibModal'];
    constructor(private $routeParams: IPlayGameParams,
                private gameService: app.services.PlayGameService,
                private $location: ng.ILocationService,
                private $uibModal: any) {
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

    showTabletCode() {
      this.selectedTab = "tabletCode";
    }

    showCommandLine() {
      this.selectedTab = "commandLine";
    }

    updateGame(game) {
      var scope = this;
      this.game = game;
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

    sendCommand(command, callback) {
      // this.gameResource.
      this.isLoading = true;
      this.game.consoleHistory += "\n" + this.game.prompt + command + "\nExecuting command on game service...";
      this.gameResource.save({gameName: this.gameName, commandLine: command}, (game: app.domain.IGameInProgress) => {
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
      if (error.xdebug_message)
        error = error.xdebug_message;
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

    onConsoleHistoryLoaded(editor, scope) {
      this.commandLineEditor = editor;
      console.log('onConsoleHistoryLoaded', scope);
      editor.on('focus', function () {
        editor.blur();
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
      this.commandLineEditor = editor;
      editor.session.gutterRenderer = {
        getWidth: function(session, lastLineNumber, config) {
          return 3 * config.characterWidth;
        },
        getText: function(session, row) {
          return ">";
        }
      };
    }

    onCommandLineChanged(e, scope) {
      if (scope.commandLine.indexOf('\n') > -1) {
        console.log('onCommandLineChanged() hit');
        scope.commandLineReadOnly = true;
        let onCommandLineProcessed = () => {
          scope.commandLine = scope.commandLine.substring(scope.commandLine.indexOf('\n')+1);
          scope.commandLineReadOnly = false;
          //scope.onCommandLineChanged();
        };
        scope.sendCommand(scope.commandLine.substring(0, scope.commandLine.indexOf('\n')), onCommandLineProcessed);
      }
    }

    onTabletCodeLoaded(editor, scope) {
      this.tabletCodeEditor = editor;
      editor.session.gutterRenderer = {
        getWidth: function(session, lastLineNumber, config) {
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
