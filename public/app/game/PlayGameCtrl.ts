module app.game {

  interface IGameInProgressModel {
    gameName: string;
    game: app.domain.IGameInProgress;
    gamesResource: ng.resource.IResourceClass<IPlayGameResource>;
  }

  interface IPlayGameParams extends ng.route.IRouteParamsService {
    gameName: string;
  }

  class PlayGameCtrl implements IGameInProgressModel {
    gameName: string;
    game: app.domain.IGameInProgress;
    gamesResource: ng.resource.IResourceClass<app.services.IPlayGameResource>;

    static $inject = ['$routeParams', 'PlayGameService', '$location'];
    constructor(private $routeParams: IPlayGameParams,
                private gameService: app.services.PlayGameService,
                private $location: ng.ILocationService) {
      this.gameName = $routeParams.gameName;

      this.game = {
        imageUrl: "loading.png",
        consoleHistory: "Connecting to server...",
        commandHistory: [],
        eol: "\n",
        prompt: "> ",
        moves: 0,
        isExiting: false
      };

      this.gameResource = gameService.playGame();

      this.gameResource.get({gameName: this.gameName}, (game: app.domain.IGameInProgress) => {
        this.game = game;
      });
    }

    sendCommand(commandLine) {
      this.gameResource.post({gameName: this.gameName, commandLine: commandLine}, (game: app.domain.IGameInProgress) => {
        this.game = game;
      });
    }
  }

  angular.module("RowdyRedApp").controller("PlayGameCtrl", PlayGameCtrl);

}
