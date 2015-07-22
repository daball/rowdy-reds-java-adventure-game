//available as of ES6, here is a polyfill for backward compatibility
//source: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/startsWith
if (!String.prototype.startsWith) {
  String.prototype.startsWith = function(searchString, position) {
    position = position || 0;
    return this.indexOf(searchString, position) === position;
  };
}

//available as of ES6, here is a polyfill for backward compatibility
//source: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/endsWith
if (!String.prototype.endsWith) {
  String.prototype.endsWith = function(searchString, position) {
      var subjectString = this.toString();
      if (position === undefined || position > subjectString.length) {
        position = subjectString.length;
      }
      position -= searchString.length;
      var lastIndex = subjectString.indexOf(searchString, position);
      return lastIndex !== -1 && lastIndex === position;
  };
}

//injects ui.ace and MapServiceModule modules
angular.module('UIControllerModule', ['ui.ace', 'GameConfigurationModule', 'MapServiceModule', 'GameEngineModule'])
  //this is the game UI controller, injects MapService, debug, appName, and GameEngine services
  .controller('UIController', function($scope, debug, appName, MapService, GameEngine) {
    $scope.appName = appName; //GomeConfiguration->appName; used to configure application name
    $scope.debug = debug; //GameConfiguration->debug; used to enable debug mode
    $scope.initialMap = MapService.buildSampleMap(); //MapService->buildSampleMap(); builds sample map
    $scope.gameEngine = GameEngine.startEngine($scope.initialMap); //GameEngine->startEngine(map); starts the game engine

    //this is where the prompt input text is stored
    $scope.prompt = "";
    $scope.promptRecall = -1;

    //Setup the two ACE editors; #console - command history; #prompt - command input
    //event handler: Sets up the command console history view, runs once
    $scope.consoleLoaded = function (consoleEditor) {
      $scope.consoleEditor = consoleEditor;
      consoleEditor.on('focus', $scope.consoleFocused); //onFocus: focus(#prompt)
      consoleEditor.setReadOnly(true);  //history is read only
      consoleEditor.setShowPrintMargin(false); //line numbers not desired
      consoleEditor.setHighlightActiveLine(false); //don't highlight the active line
    };
    //event handler: Sets focus on the prompt control
    $scope.consoleFocused = function (consoleEditor) {
      $scope.promptEditor.focus(); //#console.onFocus: focus(#prompt)
    };
    //event handler: Scrolls to the end of the console view whenever it changes
    $scope.consoleChanged = function (e) {
      var evt = (e && e[0] && e[0].data) ? e[0].data :  {};
      var consoleEditor = (e && e[1]) ? e[1] : {};
      //scroll down
      $scope.consoleEditor.scrollToLine($scope.gameEngine.gameState.commandHistory.split("\n").length, false, false, function f(x){});
    };
    //event handler: Sets up the input prompt view, runs once
    $scope.promptLoaded = function (promptEditor) {
      $scope.promptEditor = promptEditor;
      promptEditor.focus();
      promptEditor.setShowPrintMargin(false);
      promptEditor.keyBinding.origOnCommandKey = promptEditor.keyBinding.onCommandKey;
      promptEditor.keyBinding.onCommandKey = $scope.promptCommandCompletionHandler;
    };
    //event handler: whenever up or down is pressed, it should navigate through
    //the prompt command history (command completion), whenever enter is pressed
    //it should execute the command (via REPL()) and clear the prompt for the next
    //command, saving the command issued into the prompt history
    $scope.promptCommandCompletionHandler = function (e, hashId, keyCode) {
      //debugging:
      console.log(e,hashId,keyCode,$scope.prompt);
      //up key
      if (keyCode == 38) {
        if ($scope.gameEngine.gameState.promptHistory.length > 0) {
          if ($scope.promptRecall == $scope.gameEngine.gameState.promptHistory.length) {
            //store this command
            $scope.gameEngine.gameState.promptHistory.push($scope.prompt);
          }
          //move cursor up
          $scope.promptRecall--;
          //if cursor went to far, roll around to the end
          if ($scope.promptRecall < 0)
            $scope.promptRecall = $scope.gameEngine.gameState.promptHistory.length-1;
          $scope.$apply(function () {
            $scope.prompt = $scope.gameEngine.gameState.promptHistory[$scope.promptRecall].trim();
          });
        }
      }
      //down key
      else if (keyCode == 40) {
        if ($scope.gameEngine.gameState.promptHistory.length > 0) {
          if ($scope.promptRecall == $scope.gameEngine.gameState.promptHistory.length) {
            //store this command
            $scope.gameEngine.gameState.promptHistory.push($scope.prompt);
          }
          //move cursor down
          $scope.promptRecall++;
          //if cursor went to far, roll around to the start
          if ($scope.promptRecall > $scope.gameEngine.gameState.promptHistory.length-1)
            $scope.promptRecall = 0;
          $scope.$apply(function () {
            $scope.prompt = $scope.gameEngine.gameState.promptHistory[$scope.promptRecall].trim();
          });
        }
      }
      //enter key
      else if (keyCode == 13) {
        //if last command was empty (due to command completion) then
        //pop it off the array
        if ($scope.gameEngine.gameState.promptHistory.length > 0 && $scope.gameEngine.gameState.promptHistory[$scope.gameEngine.gameState.promptHistory.length-1].trim() == "")
          $scope.gameEngine.gameState.promptHistory.pop();
        //store the command to command completion history
        $scope.promptRecall = $scope.gameEngine.gameState.promptHistory.push($scope.prompt);
        //TODO:if user opens a code block { } then let them close it
        //TODO:if user opens a "" or '' statement then let them close it
        //attempt read, eval, print on closed statements
        $scope.$apply(function () {
          $scope.gameEngine.REPL($scope.prompt);
          $scope.prompt = "";
          $scope.promptEditor.focus();
        });
        //prevent this from moving down the event tree
        if (e.stopPropagation) e.stopPropagation(); //standard
        else e.cancelBubble = true; //IE
        if (e.preventDefault) e.preventDefault();
      }
      //key anything else
      else {
        //use default handler
        this.origOnCommandKey(e, hashId, keyCode);
      }
    };

  });
