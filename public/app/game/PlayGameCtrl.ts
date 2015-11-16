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

    static $inject = ['$routeParams', 'PlayGameService', '$location', '$uibModal'];
    constructor(private $routeParams: IPlayGameParams,
                private gameService: app.services.PlayGameService,
                private $location: ng.ILocationService,
                private $uibModal: any) {
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

    reconnectGame() {
      this.gameResource.get({gameName: this.gameName}, (game: app.domain.IGameInProgress) => {
        console.log(game);
        if (game.commandHistory)
          this.game = game;
        else if (game.error)
          this.handleError(game.error);
      });
    }

    sendCommand(command, callback) {
      // this.gameResource.
      this.game.consoleHistory += "\n" + this.game.prompt + command + "\nProcessing command via game service...";
      this.gameResource.save({gameName: this.gameName, commandLine: command}, (game: app.domain.IGameInProgress) => {
        console.log(game);
        if (game.commandHistory)
          this.game = game;
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
        resolve: {
          error: function () {
            return error;
          }
        }
      });
      modalInstance.result.then(this.reconnectGame, this.reconnectGame);
    }

    onConsoleHistoryLoaded(editor) {
      editor.on('focus', function () {
        editor.blur();
      });
      editor.session.gutterRenderer = {
        getWidth: function(session, lastLineNumber, config) {
          return 3 * config.characterWidth;
        },
        getText: function(session, row) {
          return row+1;
        }
      };
    }

    onConsoleHistoryChanged(e) {
      var editor = e[1];
      editor.scrollToLine(editor.session.doc.getLength(), false, true);
    }

    onCommandLineLoaded(editor) {
      editor.session.gutterRenderer = {
        getWidth: function(session, lastLineNumber, config) {
          return 3 * config.characterWidth;
        },
        getText: function(session, row) {
          return ">";
        }
      };
    }

    onCommandLineChanged() {
      var scope = this;
      if (scope.commandLine.indexOf('\n') > -1) {
        console.log('onCommandLineChanged() hit');
        scope.commandLineReadOnly = true;
        let callback = function () {
          scope.commandLine = scope.commandLine.substring(scope.commandLine.indexOf('\n')+1);
          scope.commandLineReadOnly = false;
          scope.onCommandLineChanged();
        };
        scope.sendCommand(scope.commandLine.substring(0, scope.commandLine.indexOf('\n')), callback);
      }
    }
  }

  angular.module("RowdyRedApp").controller("PlayGameCtrl", PlayGameCtrl);

}
