angular.module('MapServiceModule', ['MapBuilderModule'])

  //this is the game service, where external data is obtained for the controller
  //injects MapBuilder service
  .service("MapService", function(MapBuilder){
    var svc = this;
    svc.buildSampleMap = function buildSampleMap () {
      var mainEntrance = "mainEntrance"
        , mainHall = "mainHall"
        , treasury = "treasury"
        , directions = MapBuilder.directions
        , n = directions.n
        , s = directions.s
        , w = directions.w
        , e = directions.e
        ;

      return MapBuilder.createMap()
        //create main entrance
        .createRoom(mainEntrance)
          .describeRoom(mainEntrance, "You are standing outside a castle, looking north at a door.")
          .describeRoomDirection(mainEntrance, s, "You are at the main entrance. Have a look around.")
          .describeRoomDirection(mainEntrance, e, "You are at the main entrance. Have a look around.")
          .describeRoomDirection(mainEntrance, w, "You are at the main entrance. Have a look around.")
          .setRoomHeading(mainEntrance, "Chapter 1. Outside the castle")
          .setRoomImage(mainEntrance, "mainEntrance.jpg")

          //put a mainEntrance called 'door' on the north of mainEntrance
          .describeRoomDirection(mainEntrance, n, "You are at the main entrance. Have a look around.")
          .addMainEntranceItem(mainEntrance, n, "door")
          .describeRoomItem(mainEntrance, "door", "Enter if you dare. To enter, print something to the console in Java.")

        //create main hall
        .createRoom(mainHall)
          .describeRoom(mainHall, "You are standing in a hallway with rooms. To your south, you have the main entrance. In all other directions, there is nothing but rooms. As you navigate the hall, you wonder what each room looks like.")
          .describeRoomDirection(mainHall, n, "Seems like there should be something to my north, but there isn't. Maybe fix that, kind developer.")
          .describeRoomDirection(mainHall, s, "Seems like there should be something to my south, but there isn't. Maybe fix that, kind developer.")
          .describeRoomDirection(mainHall, w, "Seems like there should be something to my west, but there isn't. Maybe fix that, kind developer.")
          .setRoomHeading(mainHall, "Chapter 2. The castle hallway")
          .setRoomImage(mainHall, "mainHall.png")

          //put a lockedDoor called 'lock' on the east of mainHall
          .describeRoomDirection(mainHall, e, "Here lies an inscription: Enter if you dare. But the door is locked. You wonder where the key might be.")
          .addLockedDoorItem(mainHall, e, "lock", "key")
          .describeRoomItem(mainHall, "lock", "This door is locked. You wonder where the key might be.")

        //create treasury
        .createRoom(treasury)
          .describeRoom(treasury, "There is a dragon in the room. Hope you're ready to fight!")
          .setRoomHeading(treasury, "Chapter 3. Fight the dragon!")
          .setRoomImage(treasury, "treasury.jpg")

        //connect the rooms together
        .connectRooms(mainEntrance, n, mainHall) //to the north of mainEntrance lies the mainHall
        .connectRooms(mainHall, e, treasury) //to the east of mainHall lies the treasury

        //set the spawn point for the avatar
        .setSpawnPoint(mainEntrance)

        //return the map
        .getMap();
    };
    return svc;
  });
