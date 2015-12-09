module app.game {

  interface IUserManualModel {
    docMd: string;
  }

  interface IUserManualParams extends ng.route.IRouteParamsService {
    docName: string;
  }

  class UserManualCtrl implements IUserManualModel {
    docMd: string;

    static $inject = ['$routeParams', '$location', '$sanitize', '$http', '$showdown'];
    constructor(private $routeParams: IUserManualParams,
                private $location: ng.ILocationService,
                private $sanitize: any,
                private $http: any,
                private $showdown: any) {
      var docPath = './views/manual/'+$routeParams.docName+'.md';
      var scope = this;
      this.$http({
        method: 'GET',
        url: docPath
      }).then(function (res) {
        scope.docMd = res.data;
        // scope.docMd = res;
      }, function (res) {

      });
    }
  }

  angular.module("RowdyRedApp").controller("UserManualCtrl", UserManualCtrl);

}
