module app.services {
  export interface IInterceptor {
    request: Function;
    requestError: Function;
    response: Function;
    responseError: Function;
  }

  export class PHPErrorHttpInterceptor implements IInterceptor {

    static $inject = ["$injector", "$q"];
    public static Factory($injector: ng.auto.IInjectorService, $q: ng.IQService)
    {
      return new PHPErrorHttpInterceptor($injector, $q);
    }
    constructor($injector: ng.auto.IInjectorService, $q: ng.IQService) {
    }

    public request = (requestSuccess): ng.IPromise<any> => {
      return requestSuccess;
    }

    public requestError = (requestFailure): ng.IPromise<any> => {
      return requestFailure;
    }

    public response = (responseSuccess): ng.IPromise<any> => {
      //check for API request
      if (responseSuccess.config.url.indexOf('/api/') != -1 &&
          typeof responseSuccess.data == 'string') {
        //wrap the error into valid JSON for the PlayGameService for eventual consumption by PlayGameCtrl
        responseSuccess.data = {
          error: responseSuccess.data
        };
        console.log("wrapped error up like", responseSuccess.data);
      }
      return responseSuccess;
    }

    public responseError = (responseFailure): ng.IPromise<any> => {
      return responseFailure;
    }
  }

  angular.module("RowdyRedApp").config(($httpProvider: ng.IHttpProvider) => {
    $httpProvider.interceptors.push(app.services.PHPErrorHttpInterceptor.Factory);
  });
}
