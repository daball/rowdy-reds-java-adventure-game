var app;
(function (app) {
    var game;
    (function (game) {
        var ModalManualCtrl = (function () {
            function ModalManualCtrl($uibModalInstance) {
                this.$uibModalInstance = $uibModalInstance;
            }
            ModalManualCtrl.prototype.ok = function () {
                this.$uibModalInstance.close();
            };
            ModalManualCtrl.$inject = ['$uibModalInstance'];
            return ModalManualCtrl;
        })();
        angular.module("RowdyRedApp").controller("ModalManualCtrl", ModalManualCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
//# sourceMappingURL=ModalManualCtrl.js.map