describe("gameApp.mapBuilder unit tests", function() {
  beforeEach(module('gameApp.mapBuilder'));

  var $mapBuilder;

  beforeEach(inject(function (_$mapBuilder_){
    $mapBuilder = _$mapBuilder_;
  }));

  describe("$mapBuilder.directions", function() {
    it("$mapBuilder.directions should contain the cardinal directions (and abbreviated aliases)", function() {
      expect($mapBuilder.directions.north).toBe('north');
      expect($mapBuilder.directions.south).toBe('south');
      expect($mapBuilder.directions.east).toBe('east');
      expect($mapBuilder.directions.west).toBe('west');
      expect($mapBuilder.directions.n).toBe('north');
      expect($mapBuilder.directions.s).toBe('south');
      expect($mapBuilder.directions.e).toBe('east');
      expect($mapBuilder.directions.w).toBe('west');
    });
  });

  describe("$mapBuilder.oppositeDirection", function() {
    it("$mapBuilder.oppositeDirection() should evaluate the opposite of each cardinal direction", function() {
      expect($mapBuilder.oppositeDirection($mapBuilder.directions.north)).toBe('south');
      expect($mapBuilder.oppositeDirection($mapBuilder.directions.south)).toBe('north');
      expect($mapBuilder.oppositeDirection($mapBuilder.directions.east)).toBe('west');
      expect($mapBuilder.oppositeDirection($mapBuilder.directions.west)).toBe('east');
      expect($mapBuilder.oppositeDirection($mapBuilder.directions.n)).toBe('south');
      expect($mapBuilder.oppositeDirection($mapBuilder.directions.s)).toBe('north');
      expect($mapBuilder.oppositeDirection($mapBuilder.directions.e)).toBe('west');
      expect($mapBuilder.oppositeDirection($mapBuilder.directions.w)).toBe('east');
    });
  });

  describe("$mapBuilder.createMap() as $mapBuilder class", function() {
    it("$mapBuilder.createMap().createRoom() should create a new room", function() {
      var roomName = "room1";
      var map = $mapBuilder.createMap()
                  .createRoom(roomName);
      expect(map.map[0].name).toBe(roomName);
    });

    it("$mapBuilder.createMap().getRoom() should return a room", function() {
      var roomName = "room2";
      var map = $mapBuilder.createMap()
                  .createRoom(roomName);
      expect(map.getRoom(roomName).name).toBe(roomName);
    });

    it("$mapBuilder.createMap().connectRooms() should connect two rooms", function() {
      var roomName1 = "room1"
        , roomName2 = "room2";
      for (var d in $mapBuilder.directions) {
        var map = $mapBuilder.createMap()
                    .createRoom(roomName1)
                    .createRoom(roomName2);

        var direction = $mapBuilder.directions[d];
        var oppositeDirection = $mapBuilder.oppositeDirection(direction);

        map.connectRooms(roomName1, direction, roomName2);
        expect(map.getRoom(roomName1).directions[direction].jumpTo).toBe(roomName2);
        expect(map.getRoom(roomName2).directions[oppositeDirection].jumpTo).toBe(roomName1);

        map = $mapBuilder.createMap()
                    .createRoom(roomName1)
                    .createRoom(roomName2);

        map.connectRooms(roomName2, oppositeDirection, roomName1);
        expect(map.getRoom(roomName2).directions[oppositeDirection].jumpTo).toBe(roomName1);
        expect(map.getRoom(roomName1).directions[direction].jumpTo).toBe(roomName2);
      }
    });
  });
});
