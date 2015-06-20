angular.module('RUCodeCombatMapBuilder', ['ui.ace'])
  .controller('LevelMapController', function($scope) {
    $scope.rows = 10;
    $scope.cols = 10;
    $scope.grid = [[]];
    $scope.gridData = angular.toJson($scope.grid, true)
    $scope.selectedRow = null;
    $scope.selectedCol = null;

    $scope.generateGrid = function generateGrid() {
      var rows = parseInt($scope.rows);
      var cols = parseInt($scope.cols);
      var grid = [];
      var lastGrid = $scope.grid;
      for (var r = 0; r < rows; r++)
      {
        var row = [];
        var lastRow = [];
        if (r < lastGrid.length) {
          lastRow = lastGrid[r];
        }
        for (var c = 0; c < cols; c++) {
          var lastCell = { };
          if (r < lastGrid.length &&
              c < lastGrid[r].length) {
            lastCell = lastGrid[r][c];
          }
          row[c] = lastCell;
        }
        grid[r] = row;
      }
      $scope.grid = grid;
      $scope.gridData = angular.toJson($scope.grid, true)
      console.log($scope.rows, $scope.cols, $scope.grid);
    };

    $scope.generateGrid();

    $scope.importGrid = function(json) {
      $scope.grid = JSON.parse(json);
    };

    $scope.tool = "innerWall";

    $scope.selectCell = function selectCell(row, col, $event) {
      $scope.selectedRow = row;
      $scope.selectedCol = col;
      if ($event.stopPropagation) $event.stopPropagation();
      if ($event.preventDefault) $event.preventDefault();
      $event.cancelBubble = true;
      $event.returnValue = false;
    };

    var isClear = function isClear(row, col) {
      var cell = $scope.grid[row][col];
      return !cell.spawn && !cell.exit && !cell.door && !cell.enemy;
    }

    $scope.randomName = function () {
      var names = [
         "Darth Vader",
         "Darth Maul",
         "General Grievous",
         "Boba Fett",
         "Palpatine",
         "Jango Fett",
         "Count Dooku",
         "Jabba the Hutt",
         "Stormtrooper",
         "Darth Malak",
         "Asajj Ventress",
         "Wilhuff Tarkin",
         "Revan",
         "Battle Droid",
         "Exar Kun",
         "Jar Jar Binks",
         "Darth Krayt",
         "Grand Admiral Thrawn",
         "Mandalore",
         "The Yuuzhan Vong",
         "EV-A4-D",
         "Ysanne Isard"];
       return names[Math.floor(Math.random()*names.length)];
    }

    $scope.applyTool = function applyTool(row, col) {
      switch ($scope.tool) {
        case "select":
          //do nothing
          break;
        case "clear":
          if ($scope.grid[row][col].spawn)
            delete $scope.grid[row][col].spawn;
          if ($scope.grid[row][col].exit)
            delete $scope.grid[row][col].exit;
          if ($scope.grid[row][col].door)
            delete $scope.grid[row][col].door;
          if ($scope.grid[row][col].enemy)
            delete $scope.grid[row][col].enemy;
          break;
        case "innerWall": //this tool draws the inner wall where the avatar can move
          $scope.grid[row][col].walkingArea = true;
          break;
        case "outerWall": //this tool draws the outer wall where the avatar can not move
          if (isClear(row, col)) {
            if ($scope.grid[row][col].walkingArea)
              delete $scope.grid[row][col].walkingArea;
          }
          else
            alert("You cannot clear the wall of a cell containing items. Use the Clear tool to erase the cell first.");
          break;
        case "enemy": //this tool draws a door where the avatar must enter a password to continue
          if ($scope.grid[row][col].walkingArea) {
            if ($scope.grid[row][col].enemy) {}
              //do nothing
            else if (isClear(row, col))
              $scope.grid[row][col].enemy = { fightProximity: 2,
                                              hp: 10, dp: 1, speed: 2,
                                              name: $scope.randomName()
                                            };
            else
              alert("You must put a enemy inside an empty cell.");
          }
          else
            alert("You can only put a enemy inside the inner walls.")
          break;
        case "door": //this tool draws a door where the avatar must fight an enemy to continue
          if ($scope.grid[row][col].walkingArea) {
            if ($scope.grid[row][col].enemy) {}
              //do nothing
            else if (isClear(row, col))
              $scope.grid[row][col].door = { };
            else
              alert("You must put a door inside an empty cell.");
          }
          else
            alert("You can only put a door inside the inner walls.")
          break;
        case "spawn": //this tool draws the area where the avatar spawns at the beginninng of the level
          if ($scope.grid[row][col].walkingArea) {
            //remove all prior spawns
            for (var r = 0; r < $scope.grid.length; r++)
              for (var c = 0; c < $scope.grid.length; c++)
                if ($scope.grid[r][c].spawn)
                  delete $scope.grid[r][c].spawn;
            //set spawn on cell
            $scope.grid[row][col].spawn = true;
          }
          else
            alert("You can only spawn the avatar inside the inner walls.")
          break;
        case "exit": //this tool draws the area where the avatar spawns at the beginninng of the level
          if ($scope.grid[row][col].walkingArea) {
            //remove all prior exits
            for (var r = 0; r < $scope.grid.length; r++)
              for (var c = 0; c < $scope.grid.length; c++)
                if ($scope.grid[r][c].exit)
                  delete $scope.grid[r][c].exit;
            //set exit on cell
              $scope.grid[row][col].exit = true;
          }
          else
            alert("You can only exit the level inside the inner walls.")
          break;
      }
      console.log($scope.grid);
      $scope.gridData = angular.toJson($scope.grid, true);
    }
  });
