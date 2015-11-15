module app.services {
  interface IPlayGameService {
    getGames(): ng.resource.IResourceClass<IPlayGameResource>;
  }

  interface IPlayGameResource extends ng.resource.IResource<app.domain.IGameInProgress> {
  }

  export class PlayGameService implements IPlayGameService {

    static $inject = ["$resource"];
    constructor(private $resource: ng.resource.IResourceService) {

    }

    playGame(): ng.resource.IResourceClass<IPlayGameResource> {
      return this.$resource("./api/play-game.php");
    }

  }

  angular.module("RowdyRedApp").service("PlayGameService", PlayGameService);
}
