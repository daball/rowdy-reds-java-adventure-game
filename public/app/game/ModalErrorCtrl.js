var app;
(function (app) {
    var game;
    (function (game) {
        var ModalErrorCtrl = (function () {
            function ModalErrorCtrl($uibModalInstance, error) {
                this.$uibModalInstance = $uibModalInstance;
                this.error = error;
            }
            ModalErrorCtrl.prototype.ok = function () {
                this.$uibModalInstance.close();
            };
            ModalErrorCtrl.$inject = ['$uibModalInstance', 'error'];
            return ModalErrorCtrl;
        })();
        angular.module("RowdyRedApp").controller("ModalErrorCtrl", ModalErrorCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
