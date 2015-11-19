module app.game {

  interface IGameInProgressModel {
    gameName: string;
    game: app.domain.IGameInProgress;
    commandLine: string;
    commandLineReadOnly: boolean;
    gameResource: ng.resource.IResourceClass<app.services.IPlayGameResource>;
  }

  interface IPlayGameParams extends ng.route.IRouteParamsService {
    gameName: string;
  }

  class PlayGameCtrl implements IGameInProgressModel {
    gameName: string;
    game: app.domain.IGameInProgress;
    commandLine: string = "";
    commandLineReadOnly: boolean = false;
    gameResource: ng.resource.IResourceClass<app.services.IPlayGameResource>;
    consoleHistoryAceOption: any;
    commandLineAceOption: any;

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
        }
      };
    }

    updateGame(game) {
      var scope = this;
      this.game = game;
    }

    reconnectGame() {
      this.gameResource.get({gameName: this.gameName}, (game: app.domain.IGameInProgress) => {
        // console.log(game);
        if (game.commandHistory)
          this.updateGame(game);
        else if (game.error)
          this.handleError(game.error);
      });
    }

    sendCommand(command, callback) {
      // this.gameResource.
      this.game.consoleHistory += "\n" + this.game.prompt + command + "\nExecuting command on game service...";
      this.gameResource.save({gameName: this.gameName, commandLine: command}, (game: app.domain.IGameInProgress) => {
        // console.log(game);
        if (game.commandHistory)
          this.updateGame(game);
        else if (game.error)
          this.handleError(game.error);
        if (callback)
          callback();
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
      editor.session.gutterRenderer = {
        getWidth: function(session, lastLineNumber, config) {
          return 3 * config.characterWidth;
        },
        getText: function(session, row) {
          return ">";
        }
      };
    }

    onCommandLineChanged(e) {
      var scope = this;
      if (scope.commandLine.indexOf('\n') > -1) {
        console.log('onCommandLineChanged() hit');
        scope.commandLineReadOnly = true;
        let onCommandLineProcessed = () => {
          scope.commandLine = scope.commandLine.substring(scope.commandLine.indexOf('\n')+1);
          scope.commandLineReadOnly = false;
          scope.onCommandLineChanged();
        };
        scope.sendCommand(scope.commandLine.substring(0, scope.commandLine.indexOf('\n')), onCommandLineProcessed);
      }
    }
  }

  angular.module("RowdyRedApp").controller("PlayGameCtrl", PlayGameCtrl);

}
