module app.game {

  interface IGamesListModel {
    games: string[];
    isLoading: boolean;
  }

  class GamesListCtrl implements IGamesListModel {
    games: string[];
    isLoading: boolean = false;
    gamesListResource: ng.resource.IResourceClass<app.services.IPlayGameResource>;

    static $inject = ['GamesListService', '$location', '$uibModal'];
    constructor(private gamesListService: app.services.GamesListService,
                private $location: ng.ILocationService,
                private $uibModal: any) {
      this.games = [];
      this.gamesListResource = gamesListService.getGames();
      this.loadGamesList();
    }

    loadGamesList() {
      this.isLoading = true;
      this.gamesListResource.get((games: app.domain.IGamesList) => {
        console.log(games);
        if (games.error != undefined)
          this.handleError(games.error);
        else {
          this.games = games.games;
          this.isLoading = false;
          if (this.games.length == 1)
            this.playGame(this.games[0]);
        }
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
      modalInstance.result.then(() => { scope.loadGamesList() }, () => { scope.loadGamesList() });
    }

    playGame(gameName: string) {
      this.$location.url("/game/" + gameName + "/play");
    }

    goHome() {
      this.$location.url("/");
    }
  }

  angular.module("RowdyRedApp").controller("GamesListCtrl", GamesListCtrl);

}
