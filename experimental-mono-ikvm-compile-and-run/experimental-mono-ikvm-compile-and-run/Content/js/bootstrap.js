define(['require',
        'jquery/jquery',
        'bootstrap/bootstrap',
        'angular',
        'app/app'],
        function (require, $, popover, ng, app) {
          console.log(arguments);
          ng.bootstrap(document, ['app']);
});