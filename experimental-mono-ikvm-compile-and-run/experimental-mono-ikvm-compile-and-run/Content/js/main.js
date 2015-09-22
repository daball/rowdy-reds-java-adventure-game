var Content = "/Content";
var components = Content + "/components";

requirejs.config({
  paths: {
    app: Content + "/js",
    domReady: components + "/requirejs/domready",
    ace: components + "/ace/lib/ace",
    angular: components + "/angular/angular",
    'angular-ui-ace': components + '/angular-ui-ace/ui-ace',
    'angular-bootstrap': components + '/angular-bootstrap/ui-bootstrap',
    angularAMD: components + "/angularAMD/angularAMD",
    bootstrap: components + "/bootstrap/dist/js",
    jquery: components + "/jquery/src",
    sizzle: components + "/jquery/src/sizzle/dist/sizzle"
  },
  shim: {
    ace: {
      deps: ["jquery/jquery"]
    },
    angular: {
      exports: "angular"
    },
    angularAMD: ['angular'],
    'bootstrap/bootstrap': {
      deps: ["jquery/jquery"],
      exports: "$.fn.popover"
    },
    jquery: {
      exports: "jQuery"
    },
    'angular-ui-ace': {
      deps: ['angular', 'ace/ace']
    },
    'angular-bootstrap': {
      deps: ['angular', 'bootstrap']
    }
  }
});

requirejs(["app/bootstrap"]);

define(['require', 'ace/ace', 'jquery/jquery', 'bootstrap/bootstrap'], function (require, ace, $, bs) {
  window.ace = ace;
  window.$ = window.jQuery = $;
});