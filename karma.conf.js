module.exports = function(config){
  config.set({

    basePath : './',

    files : [
      'lib/jquery/dist/jquery.js',
      'lib/angular/angular.js',
      'lib/angular-route/angular-route.js',
      'lib/angular-mocks/angular-mocks.js',
      'lib/angular-ui-ace/ui-ace.js',

      'js/gameConfig.js',
      'js/mapBuilder.js',
      'js/mapService.js',
      'js/userService.js',
      'js/gameEngine.js',
      'views/game/js/game.js',
      'views/home/js/home.js',
      'js/gameApp.js',

      'js/*.spec.js'
    ],

    autoWatch : true,

    frameworks: ['jasmine'],

    browsers : ['Chrome', 'Firefox'],

    plugins : [
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-jasmine',
            'karma-junit-reporter'
            ],

    junitReporter : {
      outputFile: 'unit_test_results.xml',
      suite: 'unit'
    }

  });
};
