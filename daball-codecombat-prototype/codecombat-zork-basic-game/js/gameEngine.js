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

angular.module('GameEngineModule', ['GameConfigurationModule'])
  //allows developer to programatically generate maps
  .service("GameEngine", function(debug) {
    var svc = this;

    svc.startEngine = function createEngine(initialMap) {
      var engine = { };

      engine.initialGameState = function initialGameState() {
        return {
          commandHistory: "", //this is where the command history text is stored
          promptHistory: [],  //this is where the prompt history text is stored
          moves: 0,           //this is the number of moves it took to solve
          avatar: {
            location: "",     //this is a text value that indicates the current avatar location
            leftHand: "",     //this is the contents of the avatar left hand
            rightHand: ""     //this is the contents of the avatar right hand
          },
          map: initialMap     //this will store the map, once loaded from the game service
        };
      };

      //command handlers respond to input commands
      engine.commandHandlers = {};

      //getRoom(roomName) gets the room from the map with the name provided
      //if no name is provided, uses avatar location
      //if no name is provided and no avatar location, uses spawn point on the map
      engine.getRoom = function getRoom(roomName) {
        var findSpawn = false;
        if (!roomName) roomName = engine.gameState.avatar.location;
        if (!roomName) findSpawn = true;
        for (var r = 0; r < engine.gameState.map.length; r++) {
          var room = engine.gameState.map[r];
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
      engine.enterRoom = function enterRoom(roomName) {
        var room = engine.getRoom(roomName);
        if (room) {
          engine.gameState.avatar.location = room.name;
          return room.description;
        }
      }

      //checks current room for item like object in item parameter
      //uses Boolean AND to determine if all properties match exactly
      //uses getRoom() so:
      //uses current avatar location
      //if no avatar location, uses spawn point on the map
      engine.roomHasItemLike = function roomHasItemLike(item) {
        var room = engine.getRoom();
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
      engine.invalidCommand = function(commandLine) {
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
      engine.registerCommandHandler = function registerCommandHandler(name, fn) {
        engine.commandHandlers[name] = fn;
        return fn;
      };

      //dispatches a command to command handler by the command handler name
      engine.dispatchCommandHandler = function dispatchCommandHandler(name, commandLine) {
        return engine.commandHandlers[name](commandLine);
      };

      //clear: clears the console history
      engine.registerCommandHandler("clear", function handleClear(commandLine) {
        if (commandLine.toLowerCase().startsWith("clear")) {
          engine.gameState.commandHistory = "";
          return "Console cleared.";
        }
      });

      //exit: should exit the game, but this ain't 1993 anymore
      engine.registerCommandHandler("exit", function handleExit(commandLine) {
        if (commandLine.toLowerCase().startsWith("exit")) {
          return "Ha! Ha! This ain't 1993. Use the close button in your browser.";
        }
      });


      //reset: resets game state back to initial state
      engine.registerCommandHandler("reset", function handleReset(commandLine) {
        if (commandLine.toLowerCase().startsWith("reset") || commandLine.toLowerCase().startsWith("restart")) {
          engine.gameState = engine.initialGameState();
          return "Game restarted.\n" + engine.enterRoom("mainEntrance") + "\n";
        }
      });

      //help: prints command line help
      engine.registerCommandHandler("help",
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
          engine.gameState.moves++;
          var room = engine.getRoom();
          if (room.directions[direction]) {
            if (room.directions[direction].jumpTo) {
              if (room.directions[direction].door) {
                var mainEntrance = engine.roomHasItemLike({type: "mainEntrance"});
                var lockedDoor = engine.roomHasItemLike({type: "lockedDoor"});
                if (mainEntrance) {
                  if (mainEntrance.isOpen)
                    return engine.enterRoom(room.directions[direction].jumpTo);
                  else
                    return room.directions[direction].description || "First you have to open the door.";
                }
                else if (lockedDoor) {
                  if (lockedDoor.isLocked)
                    return room.directions[direction].description || "This door is locked. Do you have the key?";
                  else
                    return engine.enterRoom(room.directions[direction].jumpTo);
                }
              }
              else {
                return engine.enterRoom(room.directions[direction].jumpTo);
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
      engine.registerCommandHandler("north", function handleNavigateNorth(commandLine) {
        return handleNavigateDirection("north", commandLine);
      });

      //south: handles navigation in the southern direction
      engine.registerCommandHandler("south", function handleNavigateSouth(commandLine) {
        return handleNavigateDirection("south", commandLine);
      });

      //east: handles navigation in the eastern direction
      engine.registerCommandHandler("east", function handleNavigateEast(commandLine) {
        return handleNavigateDirection("east", commandLine);
      });

      //west: handles navigation in the western direction
      engine.registerCommandHandler("west", function handleNavigateWest(commandLine) {
        return handleNavigateDirection("west", commandLine);
      });

      //DEBUG ONLY
      //dump: dumps current gameState to console (and the browser's console)
      if (debug) engine.registerCommandHandler("dump", function handleDump(commandLine) {
        if (commandLine.toLowerCase() == "dump") {
          var jsonDump = JSON.stringify(engine.gameState, null, 2);
          console.log(jsonDump);
          return "For debugging purposes only, this command dumps the game state:\n" + jsonDump;
        }
      });

      //DEBUG ONLY
      //inject: injects gameState provided in commandLine and resumes the game
      if (debug) engine.registerCommandHandler("inject", function handleInject(commandLine) {
        if (commandLine.toLowerCase().startsWith("inject ")) {
          engine.gameState = JSON.parse(commandLine.substring(6));
          return "For debugging purposes only, this command injects the game state:\n" + JSON.stringify(engine.gameState, null, 2);
        }
      });

      //DEBUG ONLY
      //goto: jumps to a particular room in the game
      if (debug) engine.registerCommandHandler("goto", function handleGoto(commandLine) {
        if (commandLine.toLowerCase().startsWith("goto ")) {
          var roomName = commandLine.substring(4).trim();
          console.log('jumping to', roomName);
          return "For debugging purposes only, this command jumps to a particular room.\n" + engine.enterRoom(roomName);
        }
      });

      //inspect: inspects the room item specified or the room if no target is specified
      engine.registerCommandHandler("inspect", function handleInspect(commandLine) {
        if (commandLine.toLowerCase().startsWith("inspect") || commandLine.toLowerCase().startsWith("look at") ||
            commandLine.toLowerCase().startsWith("read") || commandLine.toLowerCase().startsWith("look")) {
              engine.gameState.moves++;
              var room = engine.getRoom();
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
      engine.registerCommandHandler("print", function handlePrint(commandLine) {
        if (
              (commandLine.startsWith("System.out.print(") || commandLine.startsWith("System.out.println("))
              && commandLine.endsWith(");")
            ) {
                engine.gameState.moves++;
                var what = "";
                if (commandLine.startsWith("System.out.print("))
                  what = commandLine.substring(17, commandLine.length-2).trim();
                else if (commandLine.startsWith("System.out.println("))
                  what = commandLine.substring(19, commandLine.length-2).trim();
                //if inside of quotes
                if (what.startsWith("\"") && what.endsWith("\"")) {
                  var mainEntrance = engine.roomHasItemLike({ type: "mainEntrance" });
                  if (mainEntrance) {
                    mainEntrance.isOpen = true;
                    return "You say " + what.substring(1, what.length-1) + " and the door opens. You wonder what\'s inside.";
                  }
                  else
                    return "You say " + what.substring(1, what.length-1) + " but nothing happens.";
                }
                //otherwise look for variables
                switch (what) {
                  case "leftHand":
                    if (engine.leftHand)
                      return "Your left hand is holding " + engine.leftHand + "."
                    else
                      return "Your left hand is empty.";
                  case "rightHand":
                    if (engine.rightHand)
                      return "Your right hand is holding " + engine.rightHand + "."
                    else
                      return "Your right hand is empty.";
                }
        }
      });


      //trims the left and right of commandLine input, then dispatches
      //to each command handler until it gets output from the function
      //if no output, it calls invalidCommand().
      engine.REPL = function (commandLine) {
        commandLine = commandLine.trim();
        if (commandLine == "")
          return;
        var ret = undefined;
        for (var h = 0 in engine.commandHandlers) {
          if ( (ret = engine.dispatchCommandHandler(h, commandLine)) != undefined )
            break;
        }
        if (ret == undefined) {
          ret = engine.invalidCommand(commandLine);
          engine.prompt = engine.prompt.trim();
        }
        engine.gameState.commandHistory += "> " + commandLine + "\n" + ret + "\n";
        return ret;
      };

      //setup initial game state
      engine.gameState = engine.initialGameState();

      //greet the user, entering the room in the map with { spawn: true }
      engine.gameState.commandHistory = "Game started.\n" + engine.enterRoom() + "\n";

      return engine;
    };
  });
