describe("GameEngineModule unit tests", function() {
  beforeEach(module('MapServiceModule'));
  beforeEach(module('GameEngineModule'));

  var GameEngine, MapService;
  beforeEach(inject(function (_GameEngine_, _MapService_) {
    GameEngine = _GameEngine_;
    MapService = _MapService_;
  }));

  it("should have a startEngine()", function () {
    expect(GameEngine.startEngine).not.toBe(undefined);
  });

  describe("GameEngine unit tests", function () {
    var engine, map;
    beforeEach(function () {
      map = MapService.buildSampleMap();
      engine = GameEngine.startEngine(map);
    });

    var findSpawn = function () {
      for (var r = 0; r < engine.gameState.map.length; r++) {
        var room = engine.gameState.map[r];
        if (room.spawn)
          return room;
      }
    };

    it("should have a gameState", function () {
      expect(engine.gameState).not.toBe(undefined);
    });

    it("should have a gameState.commandHistory", function () {
      expect(engine.gameState.commandHistory).not.toBe(undefined);
    });

    it("should have a gameState.promptHistory", function () {
      expect(engine.gameState.promptHistory).not.toBe(undefined);
    });

    it("should have a gameState.map", function () {
      expect(engine.gameState.map).not.toBe(undefined);
    });

    it("should have a gameState.moves", function () {
      expect(engine.gameState.moves).toBe(0);
    });

    it("should have a gameState.avatar", function () {
      expect(engine.gameState.avatar).not.toBe(undefined);
    });

    it("should have a gameState.avatar.leftHand", function () {
      expect(engine.gameState.avatar.leftHand).not.toBe(undefined);
    });

    it("should have a gameState.avatar.rightHand", function () {
      expect(engine.gameState.avatar.rightHand).not.toBe(undefined);
    });

    it("should have a gameState.avatar.location", function () {
      expect(engine.gameState.avatar.location).not.toBe(undefined);
    });

    it("gameState.avatar.location should be at the map\'s spawn point", function () {
      expect(engine.gameState.avatar.location).toBe(findSpawn().name);
    });

    it("getRoom() with no paramaters and no avatar location should return the spawn point", function () {
      engine.gameState.avatar.location = ""; //clear avatar location first
      expect(engine.getRoom().name).toBe(findSpawn().name);
    });

    it("getRoom() with no paramaters should return the avatar location string", function () {
      expect(engine.getRoom().name).toBe(engine.gameState.avatar.location);
    });

    it("getRoom() with roomName paramater should return the room object", function () {
      engine.gameState.avatar.location = ""; //clear avatar location first, to avoid collision
      var roomName = "treasury";
      expect(engine.getRoom(roomName).name).toBe(roomName);
    });

    it("enterRoom() with roomName paramater should enter the room and return the room.description", function () {
      var roomName = "treasury";
      expect(engine.enterRoom(roomName)).toBe(engine.getRoom(roomName).description);
      expect(engine.gameState.avatar.location).toBe(engine.getRoom(roomName).name);
    });

    it("enterRoom() with no paramaters and no avatar location should enter the spawn point and return the room.description of the spawn point", function () {
      engine.gameState.avatar.location = ""; //clear avatar location first
      var spawn = findSpawn();
      expect(engine.enterRoom()).toBe(spawn.description);
      expect(engine.gameState.avatar.location).toBe(spawn.name);
    });

    describe("GameEngine command handler unit tests", function () {
      //TODO: Write unit tests for each command handler
    });
  });
});