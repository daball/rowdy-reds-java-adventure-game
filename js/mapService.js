angular.module('gameApp.mapService', ['gameApp.mapBuilder'])

  //this is the game service, where external data is obtained for the controller
  //injects MapBuilder service
  .service("$mapService", function($mapBuilder){
    var svc = this;
    svc.buildSampleMap = function buildSampleMap () {
      var entrance = "entrance"
        , hall = "hall"
        , treasury = "treasury"
        , directions = $mapBuilder.directions
        , n = directions.n
        , s = directions.s
        , w = directions.w
        , e = directions.e
        ;

      return $mapBuilder.createMap()
        //create main entrance
        .createRoom(entrance)
          //describe main entrance
          .setRoomHeading(entrance, "Chapter 1. Outside the castle")
          .describeRoom(entrance, "You are standing outside a castle, looking north at a door.")
          .setRoomImage(entrance, "mainEntrance.png")

          //describe each direction, prints if not connected to a room
          .describeRoomDirection(entrance, n, "You are at the main entrance. Have a look around.")
          .describeRoomDirection(entrance, s, "You are at the main entrance. Have a look around.")
          .describeRoomDirection(entrance, e, "You are at the main entrance. Have a look around.")
          .describeRoomDirection(entrance, w, "You are at the main entrance. Have a look around.")

          //put a entrance called 'door' on the north of entrance
          .addMainEntranceItem(entrance, n, "door")
          .describeRoomItem(entrance, "door", "Enter if you dare. To enter, print something to the console in Java.")

        //create main hall
        .createRoom(hall)
          //describe main hall
          .setRoomHeading(hall, "Chapter 2. The castle hallway")
          .describeRoom(hall, "You are standing in a hallway with rooms. To your south, you have the main entrance. In all other directions, there is nothing but rooms. As you navigate the hall, you wonder what each room looks like.")
          .setRoomImage(hall, "mainHall.jpg")

          //describe each direction, prints if not connected to a room
          .describeRoomDirection(hall, n, "Seems like there should be something to my north, but there isn't. Maybe fix that, kind developer.")
          .describeRoomDirection(hall, s, "Seems like there should be something to my south, but there isn't. Maybe fix that, kind developer.")
          .describeRoomDirection(hall, e, "Here lies an inscription: Enter if you dare. But the door is locked. You wonder where the key might be.")
          .describeRoomDirection(hall, w, "Seems like there should be something to my west, but there isn't. Maybe fix that, kind developer.")

          //put a lockedDoor called 'lock' on the east of hall
          .addLockedDoorItem(hall, e, "lock", "key")
          .describeRoomItem(hall, "lock", "This door is locked. You wonder where the key might be.")

        //create treasury
        .createRoom(treasury)
          //describe treasury
          .describeRoom(treasury, "There is a dragon in the room. Hope you're ready to fight!")
          .setRoomHeading(treasury, "Chapter 3. Fight the dragon!")
          .setRoomImage(treasury, "treasury2.jpg")

          //describe each direction
          .describeRoomDirection(treasury, n, "There\'s nowhere to run now!")
          .describeRoomDirection(treasury, s, "There\'s nowhere to run now!")
          .describeRoomDirection(treasury, e, "There\'s nowhere to run now!")
          .describeRoomDirection(treasury, w, "There\'s nowhere to run now!")

        //connect the rooms together (each connection is a two-direction binding)
        .connectRooms(entrance, n, hall) //to the north of entrance lies the hall
        .connectRooms(hall, e, treasury) //to the east of hall lies the treasury

        //set the spawn point for the avatar
        .setSpawnPoint(entrance)

        //return the map
        .getMap();
    };
    return svc;
  });
