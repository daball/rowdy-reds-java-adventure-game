angular.module('MapBuilderModule', [])
  //allows developer to programatically generate maps
  .service("MapBuilder", function() {
    var svc = this;

    svc.directions = {
      north: "north",
      south: "south",
      east: "east",
      west: "west",

      n: "north",
      s: "south",
      e: "east",
      w: "west"
    };

    svc.oppositeDirection = function oppositeDirection (direction) {
      switch (direction) {
        case svc.directions.north: return svc.directions.south;
        case svc.directions.south: return svc.directions.north;
        case svc.directions.west: return svc.directions.east;
        case svc.directions.east: return svc.directions.west;
      }
    };

    svc.createMap = function (existingMap) {
      var MapBuilder = function MapBuilder(existingMap) {
        if ($.isArray(existingMap))
          this.map = existingMap;
        else if ($.isPlainObject(existingMap) && existingMap.map)
          this.map = existingMap
        else
          this.map = [];
        return this;
      };

      //overrides default implementation
      MapBuilder.prototype.toString = function mapToString() {
        return JSON.stringify(this, null, 2);
      };

      MapBuilder.prototype.createRoom = function createRoom(name) {
        this.map.push({
          name: name
        });
        return this;
      };

      //returns the item in the map array where
      // { name: name } name (attribute) == roomName (param)
      MapBuilder.prototype.getRoom = function getRoom(roomName) {
        for (var r = 0; r < this.map.length; r++) {
          if (this.map[r].name == roomName)
            return this.map[r];
        }
      };

      //returns the item in the room.items array where
      // { name: name } name (attribute) == itemName (param)
      MapBuilder.prototype.getRoomItem = function getRoomItem(roomName, itemName) {
        var room = this.getRoom(roomName);
        if (room && room.items)
          for (var i = 0; i < room.items.length; i++) {
            if (room.items[i].name == itemName)
              return room.items[i];
        }
      };

      //returns the createMap.map array
      MapBuilder.prototype.getMap = function getMap() {
        return this.map;
      }

      //returns the createMap.map array
      MapBuilder.prototype.setSpawnPoint = function setSpawnPoint(roomName) {
        for (var r = 0; r < this.map.length; r++) {
          if (this.map[r].spawn) {
            delete this.map[r].spawn;
          }
        }
        this.getRoom(roomName).spawn = true;
        return this;
      }

      //sets the description of the room
      MapBuilder.prototype.describeRoom = function describeRoom(roomName, roomDescription) {
        this.getRoom(roomName).description = roomDescription;
        return this;
      };

      //sets the description of the room
      MapBuilder.prototype.describeRoomDirection = function describeRoomDirection(roomName, roomDirection, description) {
        var room = this.getRoom(roomName);
        //initialize room.directions
        if (!room.directions) room.directions = {};
        //initialize room.directions[direction]
        if (!room.directions[svc.directions[roomDirection]])
          room.directions[svc.directions[roomDirection]] = {};
        room.directions[svc.directions[roomDirection]].description = description;
        return this;
      };

      //sets the header of the room (top of the page)
      MapBuilder.prototype.setRoomHeading = function setRoomHeading(roomName, roomHeading) {
        this.getRoom(roomName).heading = roomHeading;
        return this;
      };

      //sets the image for the room
      MapBuilder.prototype.setRoomImage = function setRoomImage(roomName, roomImage) {
        this.getRoom(roomName).image = roomImage;
        return this;
      };

      //do not call directly
      MapBuilder.prototype.addGenericItemToRoom = function addGenericItem(roomName, roomItem) {
        var room = this.getRoom(roomName);
        //initialize room.items
        if (!room.items) room.items = [];
        room.items.push(roomItem);
        return this;
      };

      //sets the description for the room item (so that it can be inspected)
      MapBuilder.prototype.describeRoomItem = function describeRoomItem(roomName, itemName, itemDescription) {
        this.getRoomItem(roomName, itemName).description = itemDescription;
        return this;
      };

      //do not call directly
      MapBuilder.prototype.assignDoorItemToDirection = function assignDoorToDirection(roomName, roomDirection, doorName) {
        var room = this.getRoom(roomName);
        //initialize room.directions
        if (!room.directions) room.directions = {};
        //initialize room.directions[direction]
        if (!room.directions[svc.directions[roomDirection]])
          room.directions[svc.directions[roomDirection]] = {};
        room.directions[svc.directions[roomDirection]].door = doorName;
        return this;
      };

      //assigns a main entrance door to the direction
      MapBuilder.prototype.addMainEntranceItem = function addMainEntranceItem(roomName, roomDirection, doorName) {
        //initialize room item
        this.addGenericItemToRoom(roomName, {
          type: "mainEntrance",
          name: doorName,
          isOpen: false
        });
        this.assignDoorItemToDirection(roomName, roomDirection, doorName);
        return this;
      };

      //assigns a main entrance door to the direction
      MapBuilder.prototype.addLockedDoorItem = function addMainEntranceItem(roomName, roomDirection, doorName, keyName) {
        //initialize room item
        this.addGenericItemToRoom(roomName, {
          type: "lockedDoor",
          name: doorName,
          key: keyName,
          isOpen: false,
          isLocked: true
        });
        this.assignDoorItemToDirection(roomName, roomDirection, doorName);
        return this;
      };

      //sets the direction of room1 to room2 and the opposite direction room2 to room1
      MapBuilder.prototype.connectRooms = function connectRooms(room1, room1Direction, room2) {
        room1 = this.getRoom(room1);
        room2 = this.getRoom(room2);

        //resolve room directions
        room1Direction = svc.directions[room1Direction];
        var room2Direction = svc.oppositeDirection(room1Direction);

        //initialize room.directions
        if (!room1.directions) room1.directions = {};
        if (!room2.directions) room2.directions = {};

        //initialize room.directions[direction]
        if (!room1.directions[room1Direction])
          room1.directions[room1Direction] = {};
        if (!room2.directions[room2Direction])
          room2.directions[room2Direction] = {};

        //set jumpTo attribute
        room1.directions[room1Direction].jumpTo = room2.name;
        room2.directions[room2Direction].jumpTo = room1.name;
        return this;
      };

      return new MapBuilder(existingMap);
    };

    return svc;
  })

  //this is the game service, where external data is loaded/saved
  .service("GameService", function(MapBuilder){
    var svc = this;
    svc.buildSampleMap = function buildSampleMap () {
      var mainEntrance = "mainEntrance"
        , mainHall = "mainHall"
        , treasury = "treasury";

      return MapBuilder.createMap()
        .createRoom(mainEntrance)
          .describeRoom(mainEntrance, "You are standing outside a castle, looking north at a door.")
          .describeRoomDirection(mainEntrance, MapBuilder.directions.north, "You are at the main entrance. Have a look around.")
          .describeRoomDirection(mainEntrance, MapBuilder.directions.south, "You are at the main entrance. Have a look around.")
          .describeRoomDirection(mainEntrance, MapBuilder.directions.east, "You are at the main entrance. Have a look around.")
          .describeRoomDirection(mainEntrance, MapBuilder.directions.west, "You are at the main entrance. Have a look around.")
          .setRoomHeading(mainEntrance, "Chapter 1. Outside the castle")
          .setRoomImage(mainEntrance, "mainEntrance.jpg")

          .addMainEntranceItem(mainEntrance, MapBuilder.directions.north, "door")
          .describeRoomItem(mainEntrance, "door", "Enter if you dare. To enter, print something to the console in Java.")

        .createRoom(mainHall)
          .describeRoom(mainHall, "You are standing in a hallway with rooms. To your south, you have the main entrance. In all other directions, there is nothing but rooms. As you navigate the hall, you wonder what each room looks like.")
          .describeRoomDirection(mainHall, MapBuilder.directions.north, "Seems like there should be something to my north, but there isn't. Maybe fix that, kind developer.")
          .describeRoomDirection(mainHall, MapBuilder.directions.south, "Seems like there should be something to my north, but there isn't. Maybe fix that, kind developer.")
          .describeRoomDirection(mainHall, MapBuilder.directions.east, "Here lies an inscription: Enter if you dare. But the door is locked. You wonder where the key might be.")
          .describeRoomDirection(mainHall, MapBuilder.directions.west, "Seems like there should be something to my north, but there isn't. Maybe fix that, kind developer.")
          .setRoomHeading(mainHall, "Chapter 2. The castle hallway")
          .setRoomImage(mainHall, "mainHall.png")

          .addLockedDoorItem(mainHall, MapBuilder.directions.east, "lock", "key")
          .describeRoomItem(mainHall, "lock", "This door is locked. You wonder where the key might be.")

        .connectRooms(mainEntrance, MapBuilder.directions.north, mainHall)

        .createRoom(treasury)
          .describeRoom(treasury, "There is a dragon in the room. Hope you're ready to fight!")
          .setRoomHeading(treasury, "Chapter 3. Fight the dragon!")
          .setRoomImage(treasury, "treasury.jpg")

        .connectRooms(mainHall, MapBuilder.directions.east, treasury)

        .setSpawnPoint(mainEntrance)
        .getMap();
    };
    return svc;
  });
