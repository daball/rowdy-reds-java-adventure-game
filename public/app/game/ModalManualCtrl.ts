module app.game {

  class ModalManualCtrl {
    static $inject = ['$uibModalInstance'];
    constructor(private $uibModalInstance: any) {
    }

    ok() {
      this.$uibModalInstance.close();
    }
  }

  angular.module("RowdyRedApp").controller("ModalManualCtrl", ModalManualCtrl);

}
