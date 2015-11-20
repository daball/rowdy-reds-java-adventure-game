module app.game {

  interface IGameListModel {
    games: string[];
  }

  class GameListCtrl implements IGameListModel {
    games: string[];

    static $inject = ['GameListService', '$location'];
    constructor(private gameService: app.services.GameListService,
                private $location: ng.ILocationService) {
      this.games = [];

      var gamesResource = gameService.getGames();
      gamesResource.query((games: string[]) => {
        this.games = games;
      });
    }

    playGame(gameName: string) {
      this.$location.url("/game/" + gameName + "/play");
    }

    goHome() {
      this.$location.url("/");
    }
  }

  angular.module("RowdyRedApp").controller("GameListCtrl", GameListCtrl);

}
