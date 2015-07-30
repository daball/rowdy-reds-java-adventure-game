describe('User Acceptance Tests', function() {
  beforeEach(module('gameApp'));

  // var UIController, $scope, gameEngine, gameState;
  // beforeEach(inject(function ($rootScope, $controller, $httpBackend) {
  //   $scope = $rootScope.$new();
  //   UIController = $controller('UIController', {
  //     $scope: $scope
  //   });
  //   gameEngine = $scope.gameEngine;
  //   gameState = gameEngine.gameState;
  // }));

  describe("User Navigation User Story", function () {
    describe("Given: Game running on any map, Player is in any room, A room exists in the northern direction", function () {
      it("Player navigates north by typing north (or a valid command alias) and pressing the [Enter] key", function () {
        //Verification/Then: Player enters adjacent room to the northern direction. Game displays the description of the room entered.
      })
    });
    describe("Given: Game running on any map, Player is in any room, A room exists in the southern direction", function () {
      it("Player navigates south by typing south (or a valid command alias) and pressing the [Enter] key", function () {
        //Verification/Then: Player enters adjacent room to the northern direction. Game displays the description of the room entered.
      })
    });
    describe("Given: Game running on any map, Player is in any room, A room exists in the western direction", function () {
      it("Player navigates west by typing west (or a valid command alias) and pressing the [Enter] key", function () {
        //Verification/Then: Player enters adjacent room to the northern direction. Game displays the description of the room entered.
      })
    });
    describe("Given: Game running on any map, Player is in any room, A room exists in the eastern direction", function () {
      it("Player navigates east by typing east (or a valid command alias) and pressing the [Enter] key", function () {
        //Verification/Then: Player enters adjacent room to the northern direction. Game displays the description of the room entered.
      })
    });
  });

  describe("Invalid User Navigation User Story", function () {
    describe("Given: Game running on any map, Player is in any room, A room does NOT exist in the northern direction", function () {
      it("Player navigates north by typing north (or a valid command alias) and pressing the [Enter] key", function () {
        //Verification/Then: Games will display a message or an error such as "You cannot go that way"
      })
    });
    describe("Given: Game running on any map, Player is in any room, A room does NOT exist in the southern direction", function () {
      it("Player navigates south by typing south (or a valid command alias) and pressing the [Enter] key", function () {
        //Verification/Then: Games will display a message or an error such as "You cannot go that way"
      })
    });
    describe("Given: Game running on any map, Player is in any room, A room does NOT exist in the western direction", function () {
      it("Player navigates west by typing west (or a valid command alias) and pressing the [Enter] key", function () {
        //Verification/Then: Games will display a message or an error such as "You cannot go that way"
      })
    });
    describe("Given: Game running on any map, Player is in any room, A room does NOT exist in the eastern direction", function () {
      it("Player navigates east by typing east (or a valid command alias) and pressing the [Enter] key", function () {
        //Verification/Then: Games will display a message or an error such as "You cannot go that way"
      })
    });
  });

  describe("Help Verb User Story", function () {
    describe("Player is anywhere in the game.", function () {
      it("Player requests help by typing help and pressing the [Enter] key.", function () {
        //Verification/Then: Game displays help screen to player.
      });
    });
  });

  describe("Exit Verb User Story", function () {
    describe("Player is anywhere in the game.", function () {
      it("Player exits game by typing exit and pressing the [Enter] key.", function () {
        //Verification/Then: Game exits.
      });
    });
  });

  describe("Restart Verb User Story", function () {
    describe("Player is anywhere in the game (except for the initial state).", function () {
      it("Player resets the game by typing reset and pressing the [Enter] key.", function () {
        //Verification/Then: Game resets back to the initial game state.
      });
    });
  });

  describe("Unknown Command User Story", function () {
    describe("Player is anywhere in the game.", function () {
      it("Player types no command and presses the [Enter] key.", function () {
        //Verification/Then: Game ignores input, awaits further input from Player.
      });
      it("Player types something that is NOT a command and presses the [Enter] key.", function () {
        //Verification/Then: Game provides error message such as "I do not understand."
      });
    });
  });
});
