describe("gameApp.gameConfig unit tests", function() {
  beforeEach(module('gameApp.gameConfig'));

  describe("$gameConfig", function () {
    it("should have debug present (true or false)", inject(function ($gameConfig) {
      expect($gameConfig.debug).not.toBe(undefined);
      expect($gameConfig.debug).not.toBe(null);
      expect(typeof $gameConfig.debug).toBe("boolean")
    }));

    it("should have appName present (String value)", inject(function ($gameConfig) {
      expect($gameConfig.appName).not.toBe(undefined);
      expect($gameConfig.appName).not.toBe(null);
      expect(typeof $gameConfig.appName).toBe("string")
    }));
  });
});
