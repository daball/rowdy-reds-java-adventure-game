var app;
(function (app) {
    var services;
    (function (services) {
        var PHPErrorHttpInterceptor = (function () {
            function PHPErrorHttpInterceptor($injector, $q) {
                this.request = function (requestSuccess) {
                    return requestSuccess;
                };
                this.requestError = function (requestFailure) {
                    return requestFailure;
                };
                this.response = function (responseSuccess) {
                    if (responseSuccess.config.url.indexOf('/api/') != -1 &&
                        typeof responseSuccess.data == 'string') {
                        responseSuccess.data = {
                            error: responseSuccess.data
                        };
                        console.log("wrapped error up like", responseSuccess.data);
                    }
                    return responseSuccess;
                };
                this.responseError = function (responseFailure) {
                    return responseFailure;
                };
            }
            PHPErrorHttpInterceptor.Factory = function ($injector, $q) {
                return new PHPErrorHttpInterceptor($injector, $q);
            };
            PHPErrorHttpInterceptor.$inject = ["$injector", "$q"];
            return PHPErrorHttpInterceptor;
        })();
        services.PHPErrorHttpInterceptor = PHPErrorHttpInterceptor;
        angular.module("RowdyRedApp").config(function ($httpProvider) {
            $httpProvider.interceptors.push(app.services.PHPErrorHttpInterceptor.Factory);
        });
    })(services = app.services || (app.services = {}));
})(app || (app = {}));
