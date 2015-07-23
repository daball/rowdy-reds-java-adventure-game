describe("gameApp.mapService unit tests", function() {
  beforeEach(module('gameApp.mapService'));

  var $mapService;

  beforeEach(inject(function (_$mapService_){
    $mapService = _$mapService_;
  }));

  describe("$mapService.buildSampleMap()", function() {
    var map;

    beforeEach(function () {
      map = $mapService.buildSampleMap();
    });

    var getRoom = function getRoom(roomName) {
      for (var r = 0; r < map.length; r++) {
        var room = map[r];
        if (room.name == roomName)
          return room;
      }
    }

    it("$mapService.buildSampleMap() should have \"entrance\" room", function () {
      var found = getRoom("entrance") ? true : false;
      expect(found).toBe(true);
    });

    it("$mapService.buildSampleMap() should have \"hall\" room", function () {
      var found = getRoom("hall") ? true : false;
      expect(found).toBe(true);
    });

    it("$mapService.buildSampleMap() should have \"treasury\" room", function () {
      var found = getRoom("treasury") ? true : false;
      expect(found).toBe(true);
    });

    it("$mapService.buildSampleMap() should have \"hall\" to the north of \"entrance\" (and vice-versa)", function () {
      var entrance = getRoom("entrance");
      var hall = getRoom("hall");
      expect(entrance.directions.north.jumpTo).toBe(hall.name);
      expect(hall.directions.south.jumpTo).toBe(entrance.name);
    });

    it("$mapService.buildSampleMap() should have \"treasury\" to the east of \"hall\" (and vice-versa)", function () {
      var hall = getRoom("hall");
      var treasury = getRoom("treasury");
      expect(hall.directions.east.jumpTo).toBe(treasury.name);
      expect(treasury.directions.west.jumpTo).toBe(hall.name);
    });
  });
});
