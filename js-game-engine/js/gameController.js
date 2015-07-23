angular.module('RUCodeCombatGame', ['ui.ace'])
  .controller('GameController', function($scope, $interval) {
    $scope.initialGrid = [[]];
    $scope.grid = [[]];
    $scope.codeActions = [];

    //inputs map data for the world and action data for player actions
    $scope.mapData = '[[{"spawn":true},{"exit":true}]]';
    $scope.actionData = '[{"action":"move","direction":"right","steps":1}]';

    $scope.gameState = {
      avatarLocation: [-1, -1],
      exitLocation: [-1, -1],
      isPlaying: false,
      playerExiting: false,
      algorithmFailed: false,
      score: 0,
      time: 0,
      codeSteps: 0,
      failedAttempts: 0
    };

    var findSpawnOnLoadedGrid = function () {
      for (var r = 0; r < $scope.grid.length; r++)
        for (var c = 0; c < $scope.grid[r].length; c++)
          if ($scope.grid[r][c].spawn)
            return [r, c];
    };

    var findExitOnLoadedGrid = function () {
      for (var r = 0; r < $scope.grid.length; r++)
        for (var c = 0; c < $scope.grid[r].length; c++)
          if ($scope.grid[r][c].exit)
            return [r, c];
    };

    $scope.applyActionData = function (json) {
      $scope.codeActions = JSON.parse(json);
      $scope.gameState.algorithmFailed = false;
      popAndApplyCodeAction();
    };

    $scope.applyGridAndPlay = function (json) {
      $scope.grid = JSON.parse(json);
      $scope.gameState.avatarLocation = findSpawnOnLoadedGrid();
      $scope.gameState.exitLocation = findExitOnLoadedGrid();
      $scope.gameState.isPlaying = true;
      $scope.gameState.playerExiting = false;
      $scope.gameState.score = 0;
      $scope.gameState.time = 0;
      $scope.gameState.algorithmFailed = false;
      $scope.gameState.codeSteps = 0;
      //start game timer at 4 actions/second
      $scope.gameState.gameInterval = $interval(tick, 250);
    };

    $scope.avatarAt = function (row, col) {
      return $scope.gameState.avatarLocation[0] == row &&
             $scope.gameState.avatarLocation[1] == col;
    }

    var moveAvatar = function (action) {
      //todo: obstacle detection
      //todo: enemy proximity detection
      //note that our coordinates make less sense in a 2d space, we're using [row,col] pair not [x,y] pair
      //x and y are inverted
      switch (action.direction) {
        case 'up':
          $scope.gameState.avatarLocation[0]--;
          action.steps--;
          break;
        case 'down':
          $scope.gameState.avatarLocation[0]++;
          action.steps--;
          break;
        case 'left':
          $scope.gameState.avatarLocation[1]--;
          action.steps--;
          break;
        case 'right':
          $scope.gameState.avatarLocation[1]++;
          action.steps--;
          break;
      }
      //goal: arrive at exit
      if ($scope.gameState.avatarLocation[0] == $scope.gameState.exitLocation[0] &&
          $scope.gameState.avatarLocation[1] == $scope.gameState.exitLocation[1]) {
          $scope.gameState.isPlaying = false;
          $scope.gameState.playerExiting = true;
          $scope.gameState.score += 100;
          //extra 1000 points for less than 1 minute
          if ($scope.gameState.time < 60)
            $scope.gameState.score += 1000;
      }
      if (action.steps > 0)
        return action;
    };

    var popAndApplyCodeAction = function () {
      var action = $scope.codeActions[0];

      if (action) switch (action.action) {
        case 'move':
          action = moveAvatar(action);
          if (!action)
            $scope.gameState.codeSteps++;
          break;
      };

      if (action)
        $scope.codeActions.splice(0, 1, action);
      else if ($scope.codeActions.length > 0)
        $scope.codeActions.splice(0, 1);

      if ($scope.gameState.playerExiting)
        $interval.cancel($scope.gameState.gameInterval);

      else if ($scope.codeActions.length == 0 && $scope.failedAttempts > 0) {
        $scope.gameState.algorithmFailed = true;
        $scope.gameState.failedAttempts++;
        $scope.gameState.avatarLocation = findSpawnOnLoadedGrid();
      }

      console.log($scope.gameState);
    };

    var tick = function() {
      $scope.gameState.time+=.25;
      popAndApplyCodeAction();
    };
  });
