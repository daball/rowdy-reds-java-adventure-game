define(['angularAMD', 'jquery/jquery', 'angular-ui-ace'], function (angularAMD, $, ace) {
  'use strict';
  angular.module('app', ['ui.ace'])
    .service('SampleAppSvc', [function () {
    }])
    .controller('SampleAppCtrl', [function () {
      this.sourceCode = "//Enter source code here";
    }]);
});