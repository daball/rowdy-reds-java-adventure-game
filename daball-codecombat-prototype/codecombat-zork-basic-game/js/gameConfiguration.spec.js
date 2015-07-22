describe("GameConfigurationModule unit tests", function() {
  beforeEach(module('GameConfigurationModule'));

  describe("GameConfiguration", function () {
    it("should have a debug option present", inject(function (debug) {
      expect(debug).not.toBe(undefined);
      expect(debug).not.toBe(null);
    }));
  });
});
