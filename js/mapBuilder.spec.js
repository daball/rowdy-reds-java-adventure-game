describe("MapBuilderModule unit tests", function() {
  beforeEach(module('MapBuilderModule'));

  var MapBuilder;

  beforeEach(inject(function (_MapBuilder_){
    MapBuilder = _MapBuilder_;
  }));

  describe("MapBuilder.directions", function() {
    it("MapBuilder.directions should contain the cardinal directions (and abbreviated aliases)", function() {
      expect(MapBuilder.directions.north).toBe('north');
      expect(MapBuilder.directions.south).toBe('south');
      expect(MapBuilder.directions.east).toBe('east');
      expect(MapBuilder.directions.west).toBe('west');
      expect(MapBuilder.directions.n).toBe('north');
      expect(MapBuilder.directions.s).toBe('south');
      expect(MapBuilder.directions.e).toBe('east');
      expect(MapBuilder.directions.w).toBe('west');
    });
  });

  describe("MapBuilder.oppositeDirection", function() {
    it("MapBuilder.oppositeDirection() should evaluate the opposite of each cardinal direction", function() {
      expect(MapBuilder.oppositeDirection(MapBuilder.directions.north)).toBe('south');
      expect(MapBuilder.oppositeDirection(MapBuilder.directions.south)).toBe('north');
      expect(MapBuilder.oppositeDirection(MapBuilder.directions.east)).toBe('west');
      expect(MapBuilder.oppositeDirection(MapBuilder.directions.west)).toBe('east');
      expect(MapBuilder.oppositeDirection(MapBuilder.directions.n)).toBe('south');
      expect(MapBuilder.oppositeDirection(MapBuilder.directions.s)).toBe('north');
      expect(MapBuilder.oppositeDirection(MapBuilder.directions.e)).toBe('west');
      expect(MapBuilder.oppositeDirection(MapBuilder.directions.w)).toBe('east');
    });
  });

  describe("MapBuilder.createMap() as MapBuilder class", function() {
    it("MapBuilder.createMap().createRoom() should create a new room", function() {
      var roomName = "room1";
      var map = MapBuilder.createMap()
                  .createRoom(roomName);
      expect(map.map[0].name).toBe(roomName);
    });

    it("MapBuilder.createMap().getRoom() should return a room", function() {
      var roomName = "room2";
      var map = MapBuilder.createMap()
                  .createRoom(roomName);
      expect(map.getRoom(roomName).name).toBe(roomName);
    });

    it("MapBuilder.createMap().connectRooms() should connect two rooms", function() {
      var roomName1 = "room1"
        , roomName2 = "room2";
      for (var d in MapBuilder.directions) {
        var map = MapBuilder.createMap()
                    .createRoom(roomName1)
                    .createRoom(roomName2);

        var direction = MapBuilder.directions[d];
        var oppositeDirection = MapBuilder.oppositeDirection(direction);

        map.connectRooms(roomName1, direction, roomName2);
        expect(map.getRoom(roomName1).directions[direction].jumpTo).toBe(roomName2);
        expect(map.getRoom(roomName2).directions[oppositeDirection].jumpTo).toBe(roomName1);

        map = MapBuilder.createMap()
                    .createRoom(roomName1)
                    .createRoom(roomName2);

        map.connectRooms(roomName2, oppositeDirection, roomName1);
        expect(map.getRoom(roomName2).directions[oppositeDirection].jumpTo).toBe(roomName1);
        expect(map.getRoom(roomName1).directions[direction].jumpTo).toBe(roomName2);
      }
    });
  });
});
