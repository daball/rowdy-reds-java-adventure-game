module.exports = function(config){
  config.set({

    basePath : './',

    files : [
      'lib/jquery/dist/jquery.js',
      'lib/angular/angular.js',
      'lib/angular-mocks/angular-mocks.js',
      'lib/angular-ui-ace/ui-ace.js',

      'js/gameConfiguration.js',
      'js/mapBuilder.js',
      'js/mapService.js',
      'js/gameEngine.js',
      'js/uiController.js',

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
