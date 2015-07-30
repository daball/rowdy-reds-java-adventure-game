angular.module('gameApp.views.game', ['ngRoute', 'ui.ace', 'gameApp.gameConfig', 'gameApp.gameStateService'])
  .config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/game', {
      templateUrl: './views/game/game.html',
      controller: 'GameController'
    });
  }])
  .controller('GameController', function($scope, $rootScope, $gameConfig, $gameStateService) {
    $rootScope.$gameConfig = $gameConfig;
    $scope.gameEngine = {};
    
    //callback function updates the game state from the $gameStateService
    var updateGameState = function updateGameState(error, data, status, headers, config) {
      console.log(data);
      $scope.gameEngine = data;
      $scope.gameEngine.gameState.currentRoom = $scope.gameEngine.gameState.map.rooms[$scope.gameEngine.gameState.avatarLocation];
    };
    
    //get the current game state
    $gameStateService.getGameState(updateGameState);

    //this is where the prompt input text is stored
    $scope.prompt = $gameConfig.promptDisplay;
    $scope.promptRecall = -1;
    $scope.promptSecret = "";

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
      $scope.consoleEditor.scrollToLine($scope.gameEngine.gameState.consoleHistory.split("\n").length, false, false, function f(x){});
    };
    //event handler: Sets up the input prompt view, runs once
    $scope.promptLoaded = function (promptEditor) {
      $scope.promptEditor = promptEditor;
      promptEditor.on('focus', $scope.promptFocused); //onFocus: focus(#prompt)
      promptEditor.on('changeCursor', function () { console.log('cursorChange',arguments);});
      promptEditor.focus();
      console.log(promptEditor);
      promptEditor.setShowPrintMargin(false);
      promptEditor.keyBinding.origOnCommandKey = promptEditor.keyBinding.onCommandKey;
      promptEditor.keyBinding.onCommandKey = $scope.promptCommandCompletionHandler;
    };
    $scope.promptFocused = function (promptEditor) {
      var pos = $scope.promptEditor.getCursorPosition();
      console.log("pos=",pos);
      if (pos.row == 0 && pos.column < $gameConfig.promptDisplay.length)
        $scope.promptEditor.moveCursorTo(0, $scope.prompt.length);
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
            $scope.prompt = $gameConfig.promptDisplay + $scope.gameEngine.gameState.promptHistory[$scope.promptRecall].trim();
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
            $scope.prompt = $gameConfig.promptDisplay + $scope.gameEngine.gameState.promptHistory[$scope.promptRecall].trim();
          });
        }
      }
      //left key or home key
      else if (keyCode == 37 || keyCode == 36) {
        var pos = $scope.promptEditor.getCursorPosition();
        console.log("pos=",pos);
        if (pos.row == 0 && pos.column < $gameConfig.promptDisplay.length)
          $scope.promptEditor.moveCursorTo(0, $scope.prompt.length);
      }
      //end key
      else if (keyCode == 35) {
        //process normally
      }
      //backspace key
      else if (keyCode == 8 || keyCode == 46) {
        if ($scope.prompt.length < $gameConfig.promptDisplay.length) {
          $scope.prompt = $gameConfig.promptDisplay;
        }
      }
      //enter key
      else if (keyCode == 13) {
        //if last command was empty (due to command completion) then
        //pop it off the array
        if ($scope.gameEngine.gameState.promptHistory.length > 0 && $scope.gameEngine.gameState.promptHistory[$scope.gameEngine.gameState.promptHistory.length-1].trim() == "")
          $scope.gameEngine.gameState.promptHistory.pop();
        //TODO:if user opens a code block { } then let them close it
        //TODO:if user opens a "" or '' statement then let them close it
        //attempt read, eval, print on closed statements
        $scope.$apply(function () {
          $command = $scope.prompt.substring($gameConfig.promptDisplay.length);
          $gameStateService.postCommand($command, updateGameState);
          //store the command to command completion history
          $scope.promptRecall = $scope.gameEngine.gameState.promptHistory.push($command);
          $scope.prompt = $gameConfig.promptDisplay;
          $scope.promptEditor.focus();
        });
        //prevent this from moving down the event tree
        if (e.stopPropagation) e.stopPropagation(); //standard
        else e.cancelBubble = true; //IE
        if (e.preventDefault) e.preventDefault();
      }
      //key anything else
      else {
        if ($scope.prompt.trim().startsWith('login') || $scope.prompt.trim().startsWith('register')) {
          if ($scope.prompt.trim().split(' ') > 2) {
            this.origOnCommandKey(e, hashId, keyCode);
          }
        }

        //use default handler
        this.origOnCommandKey(e, hashId, keyCode);
      }
    };

  });
