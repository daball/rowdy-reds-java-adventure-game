var app;
(function (app) {
    var game;
    (function (game) {
        var UserManualCtrl = (function () {
            function UserManualCtrl($routeParams, $location, $sanitize, $http, $showdown) {
                this.$routeParams = $routeParams;
                this.$location = $location;
                this.$sanitize = $sanitize;
                this.$http = $http;
                this.$showdown = $showdown;
                var docPath = './views/manual/' + $routeParams.docName + '.md';
                var scope = this;
                this.$http({
                    method: 'GET',
                    url: docPath
                }).then(function (res) {
                    scope.docMd = res.data;
                }, function (res) {
                });
            }
            UserManualCtrl.$inject = ['$routeParams', '$location', '$sanitize', '$http', '$showdown'];
            return UserManualCtrl;
        })();
        angular.module("RowdyRedApp").controller("UserManualCtrl", UserManualCtrl);
    })(game = app.game || (app.game = {}));
})(app || (app = {}));
//# sourceMappingURL=UserManualCtrl.js.map