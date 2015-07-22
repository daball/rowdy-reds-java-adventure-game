describe("GameConfigurationModule unit tests", function() {
  beforeEach(module('GameConfigurationModule'));

  describe("GameConfiguration", function () {
    it("should have debug present (true or false)", inject(function (debug) {
      expect(debug).not.toBe(undefined);
      expect(debug).not.toBe(null);
      expect(typeof debug).toBe("boolean")
    }));

    it("should have appName present (String value)", inject(function (appName) {
      expect(appName).not.toBe(undefined);
      expect(appName).not.toBe(null);
      expect(typeof appName).toBe("string")
    }));
  });
});
