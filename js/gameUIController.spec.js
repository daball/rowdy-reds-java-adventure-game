describe("GameUIControllerModule unit tests", function() {
  beforeEach(module('GameUIControllerModule'));

  var UIController, $scope;
  beforeEach(inject(function ($rootScope, $controller, $httpBackend) {
    $scope = $rootScope.$new();
    UIController = $controller('GameUIController', {
      $scope: $scope
    });
  }));

  it("should have a debug flag (from GameConfigurationModule)", function () {
    expect($scope.debug).not.toBe(undefined);
  });

  it("should have an initial map (from MapServiceModule.MapService)", function () {
    expect($scope.initialMap).not.toBe(undefined);
  });

  it("should have a gameEngine (from GameEngineModule)", function () {
    expect($scope.gameEngine).not.toBe(undefined);
  });

  it("should have a prompt", function () {
    expect($scope.prompt).not.toBe(undefined);
  });

  it("should have a promptRecall", function () {
    expect($scope.promptRecall).not.toBe(undefined);
  });
});
