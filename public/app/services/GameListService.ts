module app.services {
  interface IGameListService {
    getGames(): ng.resource.IResourceClass<IGameListResource>;
  }

  interface IGameListResource extends ng.resource.IResource<string[]> {
  }

  export class GameListService implements IGameListService {

    static $inject = ["$resource"];
    constructor(private $resource: ng.resource.IResourceService) {

    }

    getGames(): ng.resource.IResourceClass<IGameListResource> {
      return this.$resource("./api/list-games.php");
    }

  }

  angular.module("RowdyRedApp").service("GameListService", GameListService);
}
