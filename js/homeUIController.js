//injects ui.ace and MapServiceModule modules
angular.module('GameUIControllerModule', ['GameConfigurationModule', 'UserServiceModule'])
  //this is the game UI controller, injects MapService, debug, appName, and GameEngine services
  .controller('GameUIController', function($scope, $state, debug, appName, MapService, GameEngine) {
  });
