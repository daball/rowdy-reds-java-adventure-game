angular.module('MapServiceModule', ['MapBuilderModule'])

  //this is the game service, where external data is obtained for the controller
  //injects MapBuilder service
  .service("MapService", function(MapBuilder){
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
