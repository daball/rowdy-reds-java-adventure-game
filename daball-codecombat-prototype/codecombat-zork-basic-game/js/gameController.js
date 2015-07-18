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

angular.module('RUCodeCombatGame', ['ui.ace'])
  // .service("GameService", function($service){
  //   scope = this;
  //   scope.saveGameState = function (gameState) {
  //     $service.get()
  //   };
  // })
  .controller('GameController', function($scope) {
    $scope.gameState = {
      commandHistory: "",
      commandsTyped: 0,
      avatarLocation: "",
      leftHand: "",
      rightHand: "",
      map: []
    };
    $scope.prompt = "";

    $scope.initialMap = [
      {
        name: "mainEntrance",
        image: "mainEntrance.jpg",
        description: "You are standing outside a castle, looking at a door.",
        heading: "Chapter 1. Outside the castle",
        directions: {
          north: {
            description: "You are at the main entrance. Have a look around."
          },
          south: {
            description: "You are at the main entrance. Have a look around."
          },
          east: {
            description: "You are at the main entrance. Have a look around."
          },
          west: {
            description: "You are at the main entrance. Have a look around."
          }
        },
        items: [
          {
            type: "mainEntrance",
            name: "door",
            description: "Enter if you dare. To enter, print something to the console in Java.",
            jumpTo: "mainHall"
          }
        ]
      },
      {
        name: "mainHall",
        image: "mainHall.png",
        description: "You are standing in a hallway with rooms. To your south, you have the main entrance. To your [direction], you have [room]. ...",
        heading: "Chapter 2. The castle hallway",
        directions: {
          south: {
            description: "The main entrance.",
            jumpTo: "mainEntrance"
          },
          north: {
            description: "Seems like there should be something to my north, but there isn't. Maybe fix that, kind developer."
          },
          east: {
            description: "Seems like there should be something to my east, but there isn't. Maybe fix that, kind developer.",
            jumpTo: "treasury",
            door: "treasuryDoor",
            isLocked: true
          },
          west: {
            description: "Seems like there should be something to my west, but there isn't. Maybe fix that, kind developer."
          }
        },
        items: [
          {
            type: "door",
            name: "treasuryDoor",
            key: "treasuryKey"
          }
        ]
      },
      {
        name: "treasury",
        image: "treasury.jpg",
        description: "There is a dragon in the room. Hope you're ready to fight!",
        heading: "Chapter 3. Fight the dragon!",
        directions: {
          west: {
            jumpTo: "mainHall",
            description: "main hall"
          }
        },
        items: []
      }
    ];

    $scope.gameState.map = $scope.initialMap;

    $scope.commandHandlers = {};

    $scope.enterRoom = function enterRoom(roomName) {
      for (var r = 0; r < $scope.gameState.map.length; r++) {
        var room = $scope.gameState.map[r];
        if (room.name == roomName) {
          $scope.gameState.avatarLocation = room;
          return $scope.gameState.avatarLocation.description;
        }
      }
    }

    $scope.roomHasItemLike = function roomHasItemLike(item) {
      if ($scope.gameState.avatarLocation.items)
        for (var i = 0; i < $scope.gameState.avatarLocation.items.length; i++) {
          var found = $scope.gameState.avatarLocation.items[i];
          for (var a in item) {
            if ($scope.gameState.avatarLocation.items[i][a] != item[a]) {
              found = false;
              break;
            }
          }
          if (found)
            return $scope.gameState.avatarLocation.items[i];
        }
      return false;
    }

    $scope.invalidCommand = function(commandLine) {
      return "I'm sorry. Invalid command. Try HELP.\n";
    };

    $scope.registerCommandHandler = function registerCommandHandler(name, fn) {
      $scope.commandHandlers[name] = fn;
      return fn;
    };

    $scope.dispatchCommandHandler = function dispatchCommandHandler(name, commandLine) {
      return $scope.commandHandlers[name](commandLine);
    };

    $scope.registerCommandHandler("handleClear", function handleClear(commandLine) {
      if (commandLine.toLowerCase().startsWith("clear")) {
        $scope.gameState.commandHistory = "";
        return "Console cleared.";
      }
    });

    $scope.registerCommandHandler("handleExit", function handleExit(commandLine) {
      if (commandLine.toLowerCase().startsWith("exit")) {
        return "Ha! Ha! This ain't 1993. Use the close button in your browser.";
      }
    });

    $scope.registerCommandHandler("handleHelp",
     function handleHelp(commandLine) {
      if (commandLine.toLowerCase().startsWith("help") || commandLine.toLowerCase() == "?") {
        return "CODECOMBATZORKTHING HELP\n"
             + "===============================================================================\n"
             + "Goal: Find and kill the dragon.\n"
             + "\n"
             + "HELP - this menu\n"
             + "CLEAR - clears the command history\n"
             + "===============================================================================\n"
             + "N - move north\n"
             + "S - move south\n"
             + "W - move west\n"
             + "E - move east\n"
             + "===============================================================================\n"
             + "INSPECT (item) - inspects specified item\n"
             + "===============================================================================\n"
             + "System.out.println(\"string\"); - speaks a string of text\n"
             + "System.out.println(variable); - displays output of specified variable\n"
             + "leftHand = item - places item in left hand\n"
             + "rightHand = item - places item in right hand\n"
             + "lock = leftHand - unlocks door lock with key in left hand\n"
             + "lock = rightHand - unlocks door lock with key in right hand\n"
             + "===============================================================================\n"
             + "Item list:\n"
             + "-------------------------------------------------------------------------------\n"
             + "sword - used for attacking enemies\n"
             + "shield - for a knight's protection against enemies\n"
             + "key - door key\n"
             + "===============================================================================\n"
             + ""
             + "\n"
             + "Happy hunting!";
      }
    });

    var handleNavigateDirection = function handleNavigateDirection(direction, commandLine) {
      if (commandLine.toLowerCase().startsWith(direction) || commandLine.toLowerCase() == direction.substring(0,1)) {
        if ($scope.gameState.avatarLocation.directions[direction]) {
          if ($scope.gameState.avatarLocation.directions[direction].jumpTo) {
            if ($scope.gameState.avatarLocation.directions[direction].isLocked)
              return "This door is locked. Do you have the key?";
            else
              return $scope.enterRoom($scope.gameState.avatarLocation.directions[direction].jumpTo);
          }
          else
            return $scope.gameState.avatarLocation.directions[direction].description;
        }
        else
          return "There is nothing to the " + direction + ".";
      }
    };

    $scope.registerCommandHandler("handleNavigateNorth", function handleNavigateNorth(commandLine) {
      return handleNavigateDirection("north", commandLine);
    });

    $scope.registerCommandHandler("handleNavigateSouth", function handleNavigateSouth(commandLine) {
      return handleNavigateDirection("south", commandLine);
    });

    $scope.registerCommandHandler("handleNavigateEast", function handleNavigateEast(commandLine) {
      return handleNavigateDirection("east", commandLine);
    });

    $scope.registerCommandHandler("handleNavigateWest", function handleNavigateWest(commandLine) {
      return handleNavigateDirection("west", commandLine);
    });

    $scope.registerCommandHandler("handleDump", function handleDump(commandLine) {
      if (commandLine.toLowerCase() == "dump") {
        var jsonDump = JSON.stringify($scope.gameState, null, 2);
        console.log(jsonDump);
        return "For debugging purposes, this command dumps the game state:\n" + jsonDump;
      }
    });

    $scope.registerCommandHandler("handleInject", function handleInject(commandLine) {
      if (commandLine.toLowerCase().startsWith("inject ")) {
        $scope.gameState = JSON.parse(commandLine.substring(6));
        return "For debugging purposes, this command injects the game state:\n" + JSON.stringify($scope.gameState, null, 2);
      }
    });

    $scope.registerCommandHandler("handleInspect", function handleInspect(commandLine) {
      if (commandLine.toLowerCase().startsWith("inspect") || commandLine.toLowerCase().startsWith("look at") ||
          commandLine.toLowerCase().startsWith("read") || commandLine.toLowerCase().startsWith("look")) {
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
              if ($scope.gameState.avatarLocation.items)
                for (var i = 0; i < $scope.gameState.avatarLocation.items.length; i++) {
                  if ($scope.gameState.avatarLocation.items[i].name.toLowerCase() == what.toLowerCase()) {
                    return $scope.gameState.avatarLocation.items[i].description;
                  }
                }
            }
            else {
              var items = "";
              if ($scope.gameState.avatarLocation.items)
                for (var i = 0; i < $scope.gameState.avatarLocation.items.length; i++) {
                  items += "  +  " + $scope.gameState.avatarLocation.items[i].name + "\n"
                }
              if (items)
                items = "\n" + ("The following items in the room are available for inspection:\n" + items).trim();
              return $scope.gameState.avatarLocation.description + items;
            }
      }
    });

    $scope.registerCommandHandler("handlePrint", function handlePrint(commandLine) {
      if (
            (commandLine.startsWith("System.out.print(") || commandLine.startsWith("System.out.println("))
            && commandLine.endsWith(");")
          ) {
              var what = "";
              if (commandLine.startsWith("System.out.print("))
                what = commandLine.substring(17, commandLine.length-2).trim();
              else if (commandLine.startsWith("System.out.println("))
                what = commandLine.substring(19, commandLine.length-2).trim();
              if (what.startsWith("\"") && what.endsWith("\"")) {
                var mainEntrance = $scope.roomHasItemLike({ type: "mainEntrance" });
                if (mainEntrance) {
                  return "You speak: " + what.substring(1, what.length-1) + "\n" +
                         "The door opens.\n" + $scope.enterRoom(mainEntrance.jumpTo);
                }
                else
                  return "You speak, \"" + what.substring(1, what.length-1) + ",\" but nothing happens.";
              }
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
      else
        $scope.gameState.commandsTyped++;
      $scope.prompt = "";
      $scope.gameState.commandHistory += "> " + commandLine + "\n" + ret + "\n";
      $scope.promptEditor.focus();
    };

    //great the user
    $scope.gameState.commandHistory = "Game started.\n" + $scope.enterRoom("mainEntrance") + "\n";

    //Setup the two ACE editors
    $scope.consoleLoaded = function (consoleEditor) {
      $scope.consoleEditor = consoleEditor;
      consoleEditor.on('focus', $scope.consoleFocused);
      consoleEditor.setReadOnly(true);
      consoleEditor.setShowPrintMargin(false);
      consoleEditor.setHighlightActiveLine(false);
    };
    $scope.consoleFocused = function (consoleEditor) {
      $scope.promptEditor.focus();
    };
    $scope.consoleChanged = function (e) {
      var evt = (e && e[0] && e[0].data) ? e[0].data :  {};
      var consoleEditor = (e && e[1]) ? e[1] : {};
      //scroll down
      $scope.consoleEditor.scrollToLine($scope.gameState.commandHistory.split("\n").length, false, false, function f(x){});
    };
    $scope.promptLoaded = function (promptEditor) {
      $scope.promptEditor = promptEditor;
      promptEditor.on('focus', $scope.promptFocused);
      promptEditor.focus();
      promptEditor.setShowPrintMargin(false);
    };
    $scope.promptFocused = function (promptEditor) {
      $scope.promptEditor.moveCursorTo(0, $scope.prompt.length);
    };
    $scope.promptChanged = function (e) {
      evt = (e && e[0] && e[0].data) ? e[0].data :  {};
      promptEditor = (e && e[1]) ? e[1] : {};
      if (evt && evt.action && evt.action == "insertText") {
        if (evt.text == "\n") {
          //TODO:if user opens a code block { } then let them close it
          //TODO:if user opens a "" or '' statement then let them close it
          //attempt read, eval, print on closed statements
          $scope.REPL($scope.prompt);
        }
      }
    };

  });
