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
angular.module('GameControllerModule', ['ui.ace', 'MapServiceModule'])
  //set this to true to enable debug features, false to disable
  .value('debug', true)

  //this is the game controller, injects MapService service
  .controller('GameController', function($scope, debug, MapService) {
    $scope.initialGameState = function initialGameState() {
      return {
        commandHistory: "", //this is where the command history text is stored
        moves: 0,           //this is the number of moves it took to solve
        avatar: {
          location: "",     //this is a text value that indicates the current avatar location
          leftHand: "",     //this is the contents of the avatar left hand
          rightHand: ""     //this is the contents of the avatar right hand
        },
        map: MapService.buildSampleMap() //this will store the map, once loaded from the game service
      };
    };

    //this is where the prompt input text is stored
    $scope.prompt = "";
    $scope.promptHistory = [];
    $scope.promptRecall = -1;

    //setup initial game state
    $scope.gameState = $scope.initialGameState();

    //command handlers respond to input commands
    $scope.commandHandlers = {};

    //getRoom(roomName) gets the room from the map with the name provided
    //if no name is provided, uses avatar location
    //if no name is provided and no avatar location, uses spawn point on the map
    $scope.getRoom = function getRoom(roomName) {
      var findSpawn = false;
      if (!roomName) roomName = $scope.gameState.avatar.location;
      if (!roomName) findSpawn = true;
      for (var r = 0; r < $scope.gameState.map.length; r++) {
        var room = $scope.gameState.map[r];
        if (findSpawn && room.spawn)
          return room;
        else if (roomName && room.name == roomName)
          return room;
      }
    };

    //enters room provided by roomName, uses getRoom(roomName)
    //so:
    //if no name is provided, uses avatar location
    //if no name is provided and no avatar location, uses spawn point on the map
    $scope.enterRoom = function enterRoom(roomName) {
      var room = $scope.getRoom(roomName);
      if (room) {
        $scope.gameState.avatar.location = room.name;
        return room.description;
      }
    }

    //checks current room for item like object in item parameter
    //uses Boolean AND to determine if all properties match exactly
    //uses getRoom() so:
    //uses current avatar location
    //if no avatar location, uses spawn point on the map
    $scope.roomHasItemLike = function roomHasItemLike(item) {
      var room = $scope.getRoom();
      if (room.items)
        for (var i = 0; i < room.items.length; i++) {
          var found = room.items[i];
          for (var a in item) {
            if (room.items[i][a] != item[a]) {
              found = false;
              break;
            }
          }
          if (found)
            return room.items[i];
        }
      return false;
    }

    //called whenever no command handler responds to the command issued
    //in REPL
    $scope.invalidCommand = function(commandLine) {
      return "I'm sorry. Invalid command. Try HELP.\n";
    };

    //registers a command handler by the name
    //command handler in fn must be in the format:
    //function callbackFn(commandLine), where commandLine is the command line
    //input sent from REPL. note: commandLine is pre-trimmed by REPL.
    //if the input was valid for the command handler, the function must return
    //a value other than false
    //if the input was not valid for the command handler, the function does
    //not have to return a value, but if it does, then return false
    $scope.registerCommandHandler = function registerCommandHandler(name, fn) {
      $scope.commandHandlers[name] = fn;
      return fn;
    };

    //dispatches a command to command handler by the command handler name
    $scope.dispatchCommandHandler = function dispatchCommandHandler(name, commandLine) {
      return $scope.commandHandlers[name](commandLine);
    };

    //clear: clears the console history
    $scope.registerCommandHandler("clear", function handleClear(commandLine) {
      if (commandLine.toLowerCase().startsWith("clear")) {
        $scope.gameState.commandHistory = "";
        return "Console cleared.";
      }
    });

    //exit: should exit the game, but this ain't 1993 anymore
    $scope.registerCommandHandler("exit", function handleExit(commandLine) {
      if (commandLine.toLowerCase().startsWith("exit")) {
        return "Ha! Ha! This ain't 1993. Use the close button in your browser.";
      }
    });


    //reset: resets game state back to initial state
    $scope.registerCommandHandler("reset", function handleReset(commandLine) {
      if (commandLine.toLowerCase().startsWith("reset") || commandLine.toLowerCase().startsWith("restart")) {
        $scope.gameState = $scope.initialGameState();
        return "Game restarted.\n" + $scope.enterRoom("mainEntrance") + "\n";
      }
    });

    //help: prints command line help
    $scope.registerCommandHandler("help",
     function handleHelp(commandLine) {
      if (commandLine.toLowerCase().startsWith("help") || commandLine.toLowerCase() == "?") {
        var doubleDash = ""; for (var i = 0; i < 80; i++) doubleDash += "="; doubleDash += "\n";
        return "CODECOMBATZORKTHING HELP\n"
             + doubleDash
             + "Goal: Find and kill the dragon.\n"
             + doubleDash
             + "HELP - print this help screen\n"
             + "CLEAR - clears the command history\n"
             + "RESET - restarts the game\n"
             + (debug ? doubleDash : "")
             + (debug ? "GOTO {room} - DEBUG ONLY; enters a particular room, ignoring game rules\n" : "")
             + (debug ? "DUMP - DEBUG ONLY; dumps the game state (for saving?)\n" : "")
             + (debug ? "INJECT - DEBUG ONLY; injects a game state (for resuming/hacking?)\n" : "")
             + doubleDash
             + "N - move north\n"
             + "S - move south\n"
             + "W - move west\n"
             + "E - move east\n"
             + doubleDash
             + "INSPECT {item} - inspects specified item\n"
             + doubleDash
             + "System.out.println(\"string\"); - speaks a string of text\n"
             + "System.out.println(variable); - displays output of specified variable\n"
             + "leftHand = item - places item in left hand\n"
             + "rightHand = item - places item in right hand\n"
             + "lock = leftHand - unlocks door lock with key in left hand\n"
             + "lock = rightHand - unlocks door lock with key in right hand\n"
             + doubleDash
            //  + "Item list:\n"
            //  + "-------------------------------------------------------------------------------\n"
            //  + "sword - used for attacking enemies\n"
            //  + "shield - for a knight's protection against enemies\n"
            //  + "key - door key\n"
            //  + "===============================================================================\n"
             + "Happy hunting!";
      }
    });

    //handles navigation in any direction. note: this should not be registered as
    //a command handler. a command handler should be created in the format:
    //function (commandLine) { return handleNavigationDirection('direction', commandLine); }
    var handleNavigateDirection = function handleNavigateDirection(direction, commandLine) {
      direction = direction.toLowerCase();
      if (commandLine.toLowerCase().startsWith(direction) || commandLine.toLowerCase() == direction.substring(0,1)) {
        $scope.gameState.moves++;
        var room = $scope.getRoom();
        if (room.directions[direction]) {
          if (room.directions[direction].jumpTo) {
            if (room.directions[direction].door) {
              var mainEntrance = $scope.roomHasItemLike({type: "mainEntrance"});
              var lockedDoor = $scope.roomHasItemLike({type: "lockedDoor"});
              if (mainEntrance) {
                if (mainEntrance.isOpen)
                  return $scope.enterRoom(room.directions[direction].jumpTo);
                else
                  return room.directions[direction].description || "First you have to open the door.";
              }
              else if (lockedDoor) {
                if (lockedDoor.isLocked)
                  return room.directions[direction].description || "This door is locked. Do you have the key?";
                else
                  return $scope.enterRoom(room.directions[direction].jumpTo);
              }
            }
            else {
              return $scope.enterRoom(room.directions[direction].jumpTo);
            }
          }
          else {
            return room.directions[direction].description || "There is nothing to the " + direction + ".";
          }
        }
        else {
          return "There is nothing to the " + direction + ".";
        }
      }
    };


    //north: handles navigation in the northern direction
    $scope.registerCommandHandler("north", function handleNavigateNorth(commandLine) {
      return handleNavigateDirection("north", commandLine);
    });

    //south: handles navigation in the southern direction
    $scope.registerCommandHandler("south", function handleNavigateSouth(commandLine) {
      return handleNavigateDirection("south", commandLine);
    });

    //east: handles navigation in the eastern direction
    $scope.registerCommandHandler("east", function handleNavigateEast(commandLine) {
      return handleNavigateDirection("east", commandLine);
    });

    //west: handles navigation in the western direction
    $scope.registerCommandHandler("west", function handleNavigateWest(commandLine) {
      return handleNavigateDirection("west", commandLine);
    });

    //DEBUG ONLY
    //dump: dumps current gameState to console (and the browser's console)
    if (debug) $scope.registerCommandHandler("dump", function handleDump(commandLine) {
      if (commandLine.toLowerCase() == "dump") {
        var jsonDump = JSON.stringify($scope.gameState, null, 2);
        console.log(jsonDump);
        return "For debugging purposes only, this command dumps the game state:\n" + jsonDump;
      }
    });

    //DEBUG ONLY
    //inject: injects gameState provided in commandLine and resumes the game
    if (debug) $scope.registerCommandHandler("inject", function handleInject(commandLine) {
      if (commandLine.toLowerCase().startsWith("inject ")) {
        $scope.gameState = JSON.parse(commandLine.substring(6));
        return "For debugging purposes only, this command injects the game state:\n" + JSON.stringify($scope.gameState, null, 2);
      }
    });

    //DEBUG ONLY
    //goto: jumps to a particular room in the game
    if (debug) $scope.registerCommandHandler("goto", function handleGoto(commandLine) {
      if (commandLine.toLowerCase().startsWith("goto ")) {
        var roomName = commandLine.substring(4).trim();
        console.log('jumping to', roomName);
        return "For debugging purposes only, this command jumps to a particular room.\n" + $scope.enterRoom(roomName);
      }
    });

    //inspect: inspects the room item specified or the room if no target is specified
    $scope.registerCommandHandler("inspect", function handleInspect(commandLine) {
      if (commandLine.toLowerCase().startsWith("inspect") || commandLine.toLowerCase().startsWith("look at") ||
          commandLine.toLowerCase().startsWith("read") || commandLine.toLowerCase().startsWith("look")) {
            $scope.gameState.moves++;
            var room = $scope.getRoom();
            var what = "";
            if (commandLine.toLowerCase().startsWith("look at"))
              what = commandLine.substring(7).trim();
            else if (commandLine.toLowerCase().startsWith("look"))
              what = commandLine.substring(4).trim();
            else if (commandLine.toLowerCase().startsWith("inspect"))
              what = commandLine.substring(7).trim();
            else if (commandLine.toLowerCase().startsWith("read"))
              what = commandLine.substring(5).trim();
            if (what) {
              if (room.items)
                for (var i = 0; i < room.items.length; i++) {
                  if (room.items[i].name.toLowerCase() == what.toLowerCase()) {
                    return room.items[i].description;
                  }
                }
            }
            else {
              var items = "";
              if (room.items)
                for (var i = 0; i < room.items.length; i++) {
                  items += "  +  " + room.items[i].name + "\n"
                }
              if (items)
                items = "\n" + ("The following items in the room are available for inspection:\n" + items).trim();
              return room.description + items;
            }
      }
    });

    //print: causes the avatar to speak; in the case of the mainEntrance item type, it opens the main entrance
    $scope.registerCommandHandler("print", function handlePrint(commandLine) {
      if (
            (commandLine.startsWith("System.out.print(") || commandLine.startsWith("System.out.println("))
            && commandLine.endsWith(");")
          ) {
              $scope.gameState.moves++;
              var what = "";
              if (commandLine.startsWith("System.out.print("))
                what = commandLine.substring(17, commandLine.length-2).trim();
              else if (commandLine.startsWith("System.out.println("))
                what = commandLine.substring(19, commandLine.length-2).trim();
              //if inside of quotes
              if (what.startsWith("\"") && what.endsWith("\"")) {
                var mainEntrance = $scope.roomHasItemLike({ type: "mainEntrance" });
                if (mainEntrance) {
                  mainEntrance.isOpen = true;
                  return "You say " + what.substring(1, what.length-1) + " and the door opens. You wonder what\'s inside.\n";
                }
                else
                  return "You say " + what.substring(1, what.length-1) + " but nothing happens.";
              }
              //otherwise look for variables
              switch (what) {
                case "leftHand":
                  if ($scope.leftHand)
                    return "Your left hand is holding " + $scope.leftHand + "."
                  else
                    return "Your left hand is empty.";
                case "rightHand":
                  if ($scope.rightHand)
                    return "Your right hand is holding " + $scope.rightHand + "."
                  else
                    return "Your right hand is empty.";
              }
      }
    });


    //trims the left and right of commandLine input, then dispatches
    //to each command handler until it gets output from the function
    //if no output, it calls invalidCommand().
    $scope.REPL = function (commandLine) {
      commandLine = commandLine.trim();
      var ret = undefined;
      for (var h = 0 in $scope.commandHandlers) {
        if ( (ret = $scope.dispatchCommandHandler(h, commandLine)) != undefined )
          break;
      }
      if (ret == undefined) {
        ret = $scope.invalidCommand(commandLine);
        $scope.prompt = $scope.prompt.trim();
      }
      $scope.prompt = "";
      $scope.gameState.commandHistory += "> " + commandLine + "\n" + ret + "\n";
      $scope.promptEditor.focus();
    };

    //greet the user, entering the room in the map with { spawn: true }
    $scope.gameState.commandHistory = "Game started.\n" + $scope.enterRoom() + "\n";

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
      $scope.consoleEditor.scrollToLine($scope.gameState.commandHistory.split("\n").length, false, false, function f(x){});
    };
    //event handler: Sets up the input prompt view, runs once
    $scope.promptLoaded = function (promptEditor) {
      $scope.promptEditor = promptEditor;
      //promptEditor.on('focus', $scope.promptFocused); //disabled: doesn't add value, seems to work incorrectly
      promptEditor.focus();
      promptEditor.setShowPrintMargin(false);
      promptEditor.keyBinding.origOnCommandKey = promptEditor.keyBinding.onCommandKey;
      promptEditor.keyBinding.onCommandKey = $scope.promptCommandCompletionHandler;
    };
    //event handler: disabled, moves cursor to the end of line 1
    $scope.promptFocused = function (promptEditor) {
      //$scope.promptEditor.moveCursorTo(0, $scope.prompt.length);
    };
    //event handler: whenever up or down is pressed, it should navigate through
    //the prompt command history (command completion)
    $scope.promptCommandCompletionHandler = function (e, hashId, keyCode) {
      //key up
      if (keyCode == 38) {
        if ($scope.promptHistory.length > 0) {
          if ($scope.promptRecall == $scope.promptHistory.length) {
            //store this command
            $scope.promptHistory.push($scope.prompt);
          }
          //move cursor up
          $scope.promptRecall--;
          //if cursor went to far, roll around to the end
          if ($scope.promptRecall < 0)
            $scope.promptRecall = $scope.promptHistory.length-1;
          $scope.$apply(function () {
            $scope.prompt = $scope.promptHistory[$scope.promptRecall].trim();
          });
        }
      }
      //key down
      else if (keyCode == 40) {
        if ($scope.promptHistory.length > 0) {
          if ($scope.promptRecall == $scope.promptHistory.length) {
            //store this command
            $scope.promptHistory.push($scope.prompt);
          }
          //move cursor down
          $scope.promptRecall++;
          //if cursor went to far, roll around to the start
          if ($scope.promptRecall > $scope.promptHistory.length-1)
            $scope.promptRecall = 0;
          $scope.$apply(function () {
            $scope.prompt = $scope.promptHistory[$scope.promptRecall].trim();
          });
        }
      }
      //key anything else
      else {
        //use default handler
        this.origOnCommandKey(e, hashId, keyCode);
      }
    };
    //event handler: whenever an insertText action occurs with text == "\n"
    //send the command to REPL() for processing of the input text in
    //$scope.prompt (provided by the Angular binding in the ui-ace control,
    //in ng-model)
    $scope.promptChanged = function (e) {
      evt = (e && e[0] && e[0].data) ? e[0].data :  {};
      promptEditor = (e && e[1]) ? e[1] : {};
      if (evt && evt.action && evt.action == "insertText") {
        if (evt.text == "\n") {
          //if last command was empty (due to command completion) then
          //pop it off the array
          if ($scope.promptHistory.length > 0 && $scope.promptHistory[$scope.promptHistory.length-1].trim() == "")
            $scope.promptHistory.pop();
          //store the command to command completion history
          $scope.promptRecall = $scope.promptHistory.push($scope.prompt);
          //TODO:if user opens a code block { } then let them close it
          //TODO:if user opens a "" or '' statement then let them close it
          //attempt read, eval, print on closed statements
          $scope.REPL($scope.prompt);
        }
      }
    };

  });
