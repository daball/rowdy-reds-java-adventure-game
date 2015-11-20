module app.game {

  interface IGameListModel {
    games: string[];
    isLoading: boolean;
  }

  class GameListCtrl implements IGameListModel {
    games: string[];
    isLoading: boolean = false;

    static $inject = ['GameListService', '$location'];
    constructor(private gameService: app.services.GameListService,
                private $location: ng.ILocationService) {
      this.games = [];

      var gamesResource = gameService.getGames();
      this.isLoading = true;
      gamesResource.query((games: string[]) => {
        this.games = games;
        this.isLoading = false;
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
