module app.services {
  export interface IGamesListService {
    getGames(): ng.resource.IResourceClass<IGamesListResource>;
  }

  export interface IGamesListResource extends ng.resource.IResource<app.domain.IGamesList> {
  }

  export class GamesListService implements IGamesListService {

    static $inject = ["$resource"];
    constructor(private $resource: ng.resource.IResourceService) {

    }

    getGames(): ng.resource.IResourceClass<IGamesListResource> {
      return this.$resource("./api/list-games.php");
    }

  }

  angular.module("RowdyRedApp").service("GamesListService", GamesListService);
}
