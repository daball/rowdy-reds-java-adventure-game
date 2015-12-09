module app.game {

  interface IMainMenuModel {
  }

  class MainMenuCtrl implements IMainMenuModel {
    static $inject = ["$location"];
    constructor(private $location: ng.ILocationService) {
    }

    startNewGame() {
      this.$location.url("/games")
    }
  }

  angular.module("RowdyRedApp").controller("MainMenuCtrl", MainMenuCtrl);

}
