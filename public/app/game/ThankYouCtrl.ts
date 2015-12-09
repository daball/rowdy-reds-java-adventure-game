module app.game {

  interface IThankYouModel {
    gameName: string;
  }

  interface IPlayGameParams extends ng.route.IRouteParamsService {
    gameName: string;
  }

  class ThankYouCtrl implements IThankYouModel {
    gameName: string;

    static $inject = ['$routeParams', 'PlayGameService', '$location', '$uibModal'];
    constructor(private $routeParams: IPlayGameParams,
                private gameService: app.services.PlayGameService,
                private $location: ng.ILocationService,
                private $uibModal: any) {
      this.gameName = $routeParams.gameName;
    }

    goHome() {
      this.$location.url("/");
    }

  }

  angular.module("RowdyRedApp").controller("ThankYouCtrl", ThankYouCtrl);

}
