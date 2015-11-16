module app.game {

  interface IModalErrorModel {
    error: string;
  }

  class ModalErrorCtrl implements IModalErrorModel {
    static $inject = ['$uibModalInstance', 'error'];
    constructor(private $uibModalInstance: any,
                public error: string) {
    }

    ok() {
      this.$uibModalInstance.close();
    }
  }

  angular.module("RowdyRedApp").controller("ModalErrorCtrl", ModalErrorCtrl);

}
