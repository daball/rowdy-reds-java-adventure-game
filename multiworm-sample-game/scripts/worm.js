// Copyright (c) 2011-2012 Turbulenz Limited
/*global TurbulenzEngine: false*/
/*global SimpleRendering: false*/
/*global Camera: false*/
/*global Scene: false*/
/*global SceneNode: false*/
/*global SceneLoader: false*/
/*global window: false*/
//
// AppScene - creates and stores the application scene
//
var AppScene = (function () {
    function AppScene() {
    }
    // Error callback - uses window alert
    AppScene.prototype.errorCallback = function (msg) {
        window.alert(msg);
    };

    // Tests whether there are things being loaded
    AppScene.prototype.hasLoaded = function () {
        var sceneLoader = this.sceneLoader;
        if (sceneLoader) {
            if (sceneLoader.complete()) {
                this.sceneLoader = null;

                this.renderer.updateShader(this.managers.shaderManager);

                this.createMaterials();

                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    };

    // Loads the cube asset
    AppScene.prototype.loadCube = function () {
        var devices = this.devices;
        var managers = this.managers;
        var mappingTable = this.mappingTable;

        var request = function requestFn(assetName, onload) {
            return TurbulenzEngine.request(mappingTable.getURL(assetName), onload);
        };

        var cubeLoadingParameters = {
            scene: this.scene,
            append: true,
            assetPath: "models/cube.dae",
            keepLights: false,
            keepCameras: false,
            graphicsDevice: devices.graphicsDevice,
            mathDevice: devices.mathDevice,
            textureManager: managers.textureManager,
            shaderManager: managers.shaderManager,
            effectManager: managers.effectManager,
            requestHandler: this.requestHandler,
            request: request
        };

        this.sceneLoader.load(cubeLoadingParameters);
    };

    AppScene.prototype.createMaterials = function () {
        var scene = this.scene;
        var devices = this.devices;
        var graphicsDevice = devices.graphicsDevice;
        var mathDevice = devices.mathDevice;
        var v4Build = mathDevice.v4Build;
        var managers = this.managers;
        var materials;
        var materialName;
        var material;

        // The materials we will use
        this.materials = {
            blueMaterial: {
                effect: "constant",
                meta: {
                    materialcolor: true
                },
                parameters: {
                    materialColor: v4Build.call(mathDevice, 0.0, 0.0, 1.0, 1.0)
                }
            },
            playerMaterial: {
                effect: "constant",
                meta: {
                    materialcolor: true
                },
                parameters: {
                    materialColor: this.playerColor
                }
            },
            redMaterial: {
                effect: "constant",
                meta: {
                    materialcolor: true
                },
                parameters: {
                    materialColor: v4Build.call(mathDevice, 1.0, 0.0, 0.0, 1.0)
                }
            },
            grayMaterial: {
                effect: "constant",
                meta: {
                    materialcolor: true
                },
                parameters: {
                    materialColor: v4Build.call(mathDevice, 0.5, 0.5, 0.5, 1.0)
                }
            }
        };

        materials = this.materials;

        for (materialName in materials) {
            if (materials.hasOwnProperty(materialName)) {
                material = materials[materialName];

                if (scene.loadMaterial(graphicsDevice, managers.textureManager, managers.effectManager, materialName, material)) {
                    material.loaded = true;
                    scene.getMaterial(materialName).reference.add();
                } else {
                    this.errorCallback("Failed to load material: " + materialName);
                }
            }
        }
    };

    // Populates the scene with board and worm
    AppScene.prototype.setupScene = function () {
        // Cached vars
        var scene = this.scene;
        var gameSettings = this.game.gameSettings;
        var mathDevice = this.devices.mathDevice;
        var v3Build = mathDevice.v3Build;

        // Board dimensions
        var boardCenter = v3Build.call(mathDevice, 0, 0, 0);
        var boardSpacing = gameSettings.boardSpacing;
        var horizontalCubes = gameSettings.width;
        var verticalCubes = gameSettings.height;
        var maxPlayers = gameSettings.maxPlayers;

        this.setupCamera();

        // Create our reference cubeNode for board, worm and food
        this.createCubeNodes(maxPlayers);

        // Setup root node and children for use in game
        this.createNodeStructure(maxPlayers);

        this.createBoard(boardCenter, boardSpacing, horizontalCubes, verticalCubes);

        scene.update();
    };

    // Setup the camera
    AppScene.prototype.setupCamera = function () {
        var mathDevice = this.devices.mathDevice;
        var v3Build = mathDevice.v3Build;

        // Camera looks along -ive z direction towards origin - has 60 degree FOV
        var cameraPosition = v3Build.call(mathDevice, -0.5, -25.0, 25.0);
        var cameraTarget = v3Build.call(mathDevice, -0.5, 0.0, 0.0);
        var worldUp = v3Build.call(mathDevice, 0.0, 1.0, 0.0);
        var halfFov = Math.tan(30 * (Math.PI / 180));
        var camera = Camera.create(mathDevice);

        camera.recipViewWindowX = (1.0 / halfFov);
        camera.recipViewWindowY = (1.0 / halfFov);
        camera.updateProjectionMatrix();
        camera.lookAt(cameraTarget, worldUp, cameraPosition);
        camera.updateViewMatrix();

        this.camera = camera;
    };

    // Resets the worm to starting state
    AppScene.prototype.resetWorm = function (wormIndex) {
        var wormNode = this.wormNodes[wormIndex];
        var wormPartsNodes = this.wormPartsNodes[wormIndex];

        var n = wormPartsNodes.length;
        while (n) {
            n -= 1;
            var wormPartsNode = wormPartsNodes[n];
            wormNode.removeChild(wormPartsNode);
            wormPartsNode.destroy();
        }

        wormPartsNodes.length = 0;
    };

    // Creates our reference cube node to be duplicated
    AppScene.prototype.createCubeNodes = function (maxPlayers) {
        // Find and get renderable out of loaded node
        var loadedCubeNode = this.scene.findNode("cube");
        var boardCubeRenderable = loadedCubeNode.renderables[0].clone();
        var wormCubeRenderable = boardCubeRenderable.clone();
        var otherWormCubeRenderable = boardCubeRenderable.clone();
        var foodCubeRenderable = boardCubeRenderable.clone();

        // Set the materials
        this.setMaterial(boardCubeRenderable, "blueMaterial");
        this.setMaterial(wormCubeRenderable, "playerMaterial");
        this.setMaterial(otherWormCubeRenderable, "grayMaterial");
        this.setMaterial(foodCubeRenderable, "redMaterial");

        var boardCubeNodeParameters = {
            name: "boardCube",
            dynamic: false,
            disabled: false
        };
        var wormCubeNodeParameters = {
            name: "wormCube",
            dynamic: true,
            disabled: false
        };
        var foodCubeNodeParameters = {
            name: "foodCube",
            dynamic: true,
            disabled: false
        };

        // Nodes to be used as template for board, worm, and food cubes
        var boardCubeNode = SceneNode.create(boardCubeNodeParameters);
        boardCubeNode.addRenderable(boardCubeRenderable);
        this.boardCubeNode = boardCubeNode;

        this.wormCubeRenderable = wormCubeRenderable;
        this.otherWormCubeRenderable = otherWormCubeRenderable;

        var wormCubeNode = SceneNode.create(wormCubeNodeParameters);
        this.wormCubeNode = wormCubeNode;

        var foodCubeNodes = this.foodCubeNodes;
        var n;
        for (n = 0; n < maxPlayers; n += 1) {
            var foodCubeNode = SceneNode.create(foodCubeNodeParameters);
            foodCubeNode.addRenderable(n === 0 ? foodCubeRenderable : foodCubeRenderable.clone());
            foodCubeNodes[n] = foodCubeNode;
        }
    };

    // Sets up the root node and children for the game
    AppScene.prototype.createNodeStructure = function (maxPlayers) {
        // Structure is game as root node, with board and worm as children
        var scene = this.scene;
        var gameNode;
        var boardNode;
        var wormNode;
        var loadedCubeNode;
        var gameNodeParameters = {
            name: "game",
            dynamic: false,
            disabled: false
        };
        var boardNodeParameters = {
            name: "board",
            dynamic: false,
            disabled: false
        };
        var wormNodeParameters = {
            name: "worm",
            dynamic: false,
            disabled: false
        };

        // Create the game, board and worm nodes
        gameNode = SceneNode.create(gameNodeParameters);
        this.gameNode = gameNode;

        boardNode = SceneNode.create(boardNodeParameters);
        this.boardNode = boardNode;
        gameNode.addChild(boardNode);

        var n;
        for (n = 0; n < maxPlayers; n += 1) {
            wormNode = SceneNode.create(wormNodeParameters);
            this.wormNodes[n] = wormNode;
            this.wormPartsNodes[n] = [];
            gameNode.addChild(wormNode);
        }

        var foodCubeNodes = this.foodCubeNodes;
        for (n = 0; n < maxPlayers; n += 1) {
            gameNode.addChild(foodCubeNodes[n]);
        }

        // Remove loaded node
        loadedCubeNode = this.scene.findNode("cube");
        scene.removeRootNode(loadedCubeNode);

        // Add game as root node
        scene.addRootNode(gameNode);
    };

    // Create the back grid to play on
    AppScene.prototype.createBoard = function (boardCenter, boardSpacing, horizontalCubes, verticalCubes) {
        // Cached vars
        var mathDevice = this.devices.mathDevice;
        var m43BuildTranslation = mathDevice.m43BuildTranslation;

        var boardNode = this.boardNode;
        var boardCubeNode = this.boardCubeNode;
        var boardWidth = (horizontalCubes * boardSpacing);
        var boardHeight = (verticalCubes * boardSpacing);
        var minX = (boardCenter[0] - (boardWidth / 2));
        var minY = (boardCenter[1] - (boardHeight / 2));
        var z = boardCenter[2];

        var i, j;
        var x, y;
        var newCubeNode, local;

        x = minX;
        for (i = 0; i < horizontalCubes; i += 1) {
            y = minY;
            for (j = 0; j < verticalCubes; j += 1) {
                // Create new node, position it, and add to scene
                newCubeNode = boardCubeNode.clone();
                boardNode.addChild(newCubeNode);

                // Reset node transform
                local = newCubeNode.getLocalTransform();

                m43BuildTranslation.call(mathDevice, x, y, z, local);

                newCubeNode.setLocalTransform(local);

                y += boardSpacing;
            }

            x += boardSpacing;
        }
    };

    // Update function - called every frame
    AppScene.prototype.update = function () {
        this.updateGameScene();
        this.updateCamera();
    };

    // Updates the camera
    AppScene.prototype.updateCamera = function () {
        var camera = this.camera;
        var graphicsDevice = this.devices.graphicsDevice;
        var deviceWidth = graphicsDevice.width;
        var deviceHeight = graphicsDevice.height;
        var aspectRatio = (deviceWidth / deviceHeight);

        if (aspectRatio !== camera.aspectRatio) {
            camera.aspectRatio = aspectRatio;
            camera.updateProjectionMatrix();
        }
        camera.updateViewProjectionMatrix();
    };

    // Updates the worm node
    AppScene.prototype.updateGameScene = function () {
        var game = this.game;
        var state = game.state;
        var currentState = game.currentState;
        var myWorm = game.getWorm(game.myWormIndex);
        var myColor = (myWorm && myWorm.playerInfo.color) || this.currentPlayerColor || "green";
        var mathDevice = this.devices.mathDevice;
        var v4Build = mathDevice.v4Build;

        if (currentState === state.DEAD) {
            if (this.previousGameState !== currentState) {
                this.clearColor = v4Build.call(mathDevice, 1, 0, 0, 1, this.clearColor);
            } else {
                return;
            }
        } else if (currentState === state.PLAY) {
            if (this.previousGameState !== currentState) {
                this.resetWorm(game.myWormIndex);

                this.scene.update();

                this.clearColor = v4Build.call(mathDevice, 0, 0, 0, 1, this.clearColor);
            }
        } else if (currentState === state.ERROR) {
            if (this.previousGameState !== currentState) {
                this.clearColor = v4Build.call(mathDevice, 0.8, 0.4, 0.2, 1, this.clearColor);
            } else {
                return;
            }
        }

        if (myColor !== this.currentPlayerColor) {
            if (myColor === "yellow") {
                v4Build.call(mathDevice, 1, 1, 0, 1, this.playerColor);
            } else {
                v4Build.call(mathDevice, 0, 1, 0, 1, this.playerColor);
            }
            this.currentPlayerColor = myColor;
        }

        this.previousGameState = currentState;

        this.updateTransforms();
    };

    // Updates the local transforms of the worm part nodes
    AppScene.prototype.moveWormNodes = function (wormIndex) {
        var mathDevice = this.devices.mathDevice;
        var m43BuildTranslation = mathDevice.m43BuildTranslation;
        var game = this.game;
        var gameSettings = game.gameSettings;
        var boardSpacing = gameSettings.boardSpacing;
        var offsetX = (gameSettings.width / 2);
        var offsetY = (gameSettings.height / 2);

        var worm = game.getWorm(wormIndex);
        var partsPositionX = worm.partsPositionX;
        var partsPositionY = worm.partsPositionY;
        var numParts = partsPositionX.length;

        var wormPartNodes = this.wormPartsNodes[wormIndex];
        var numWormPartNodes = wormPartNodes.length;
        var isOther = (wormIndex !== game.myWormIndex);
        var wormNode = this.wormNodes[wormIndex];
        var wormCubeNode = this.wormCubeNode;
        var i;

        if (numParts < numWormPartNodes) {
            this.resetWorm(wormIndex);
            numWormPartNodes = 0;
        }

        if (numWormPartNodes < numParts) {
            for (i = numWormPartNodes; i < numParts; i += 1) {
                var newWormCubeNode = wormCubeNode.clone();
                if (isOther) {
                    newWormCubeNode.addRenderable(this.otherWormCubeRenderable.clone());
                } else {
                    newWormCubeNode.addRenderable(this.wormCubeRenderable.clone());
                }
                wormNode.addChild(newWormCubeNode);
                wormPartNodes.push(newWormCubeNode);
            }
        }

        for (i = 0; i < numParts; i += 1) {
            var wormPartNode = wormPartNodes[i];

            var local = wormPartNode.getLocalTransform();

            m43BuildTranslation.call(mathDevice, (partsPositionX[i] - offsetX) * boardSpacing, (partsPositionY[i] - offsetY) * boardSpacing, 1, local);

            wormPartNode.setLocalTransform(local);
        }
    };

    // Update scene from game
    AppScene.prototype.updateTransforms = function () {
        var mathDevice = this.devices.mathDevice;
        var game = this.game;
        var gameSettings = game.gameSettings;
        var boardSpacing = gameSettings.boardSpacing;
        var offsetX = (gameSettings.width / 2);
        var offsetY = (gameSettings.height / 2);
        var maxPlayers = gameSettings.maxPlayers;
        var foodCubeNodes = this.foodCubeNodes;
        var local;

        var n;
        for (n = 0; n < maxPlayers; n += 1) {
            // Food
            var food = game.getFood(n);

            var foodCubeNode = foodCubeNodes[n];

            local = foodCubeNode.getLocalTransform();

            mathDevice.m43BuildTranslation((food.x - offsetX) * boardSpacing, (food.y - offsetY) * boardSpacing, 1, local);

            foodCubeNode.setLocalTransform(local);

            // Worm
            this.moveWormNodes(n);
        }

        this.scene.update();
    };

    // Sets node renderable with material specified
    AppScene.prototype.setMaterial = function (renderable, materialName) {
        var material;

        if (renderable) {
            material = this.materials[materialName];
            if (material && material.loaded) {
                renderable.setMaterial(this.scene.getMaterial(materialName));
            }
        }
    };

    AppScene.create = // AppScene constructor function
    function (devices, managers, requestHandler, mappingTable, game) {
        var mathDevice = devices.mathDevice;

        var appScene = new AppScene();

        appScene.renderer = null;
        appScene.materials = {};
        appScene.devices = devices;
        appScene.requestHandler = requestHandler;
        appScene.managers = managers;
        appScene.mappingTable = mappingTable;
        appScene.game = game;
        appScene.previousGameState = null;
        appScene.gameNode = null;
        appScene.boardNode = null;
        appScene.wormNodes = [];
        appScene.boardCubeNode = null;
        appScene.wormCubeNode = null;
        appScene.foodCubeNodes = [];
        appScene.camera = null;
        appScene.scene = Scene.create(devices.mathDevice);
        appScene.sceneLoader = SceneLoader.create();
        appScene.clearColor = mathDevice.v4Build(0, 0, 0, 1);
        appScene.playerColor = mathDevice.v4Build(0, 1, 0, 1);
        appScene.currentPlayerColor = '';

        // Stores references to worm parts for setting local transforms quickly
        appScene.wormPartsNodes = [];

        // Load the scene
        var v3Build = mathDevice.v3Build;
        var globalLightPosition = v3Build.call(mathDevice, 20.0, 0.0, 100.0);
        var ambientColor = v3Build.call(mathDevice, 0.3, 0.3, 0.4);

        var renderer = SimpleRendering.create(devices.graphicsDevice, mathDevice, managers.shaderManager, managers.effectManager);

        appScene.renderer = renderer;

        renderer.setGlobalLightPosition(globalLightPosition);
        renderer.setAmbientColor(ambientColor);

        appScene.loadCube();

        return appScene;
    };
    return AppScene;
})();
// Copyright (c) 2011-2012 Turbulenz Limited
/*global TurbulenzEngine: false*/
/*global Badge: false*/
//
// GameBadges: Class to manage game badges, and their html elements
//
var GameBadges = (function () {
    function GameBadges() {
    }
    // Adds the badges structure into the element div
    GameBadges.prototype.init = function () {
        var that = this;

        function localInitialiseBadges(badges) {
            that.initialiseBadges(badges);
        }

        function localErrorCallback(msg, status) {
            that.initialiseBadgesErrorCallback(msg, status);
        }

        // Store and categorise badges
        this.badgesManager.listBadges(localInitialiseBadges, localErrorCallback);
    };

    // Error function when we fail to list & initialise badges
    GameBadges.prototype.initialiseBadgesErrorCallback = function (msg, status) {
        this.isActive = false;
        this.onInitialization();
    };

    // Add all badges to unachieved badge dictionary
    GameBadges.prototype.initialiseBadges = function (badges) {
        var that = this;
        var unachievedBadges = this.unachievedBadges;

        function localInitialiseUserBadges(badges) {
            that.initialiseUserBadges(badges);
        }

        function localErrorCallback(msg, status) {
            that.initialiseBadgesErrorCallback(msg, status);
        }

        var i;
        var name;
        var badge;

        var length = badges.length;
        for (i = 0; i < length; i += 1) {
            badge = badges[i];
            name = badge.key;

            // Create an unachieved badge
            unachievedBadges[name] = Badge.create(badge.total, badge.predescription, badge.description, badge.title);
        }

        // Add user badges to badges dictionary
        this.badgesManager.listUserBadges(localInitialiseUserBadges, localErrorCallback);
    };

    // Add user badges to unachieved and achieved badge dictionaries
    GameBadges.prototype.initialiseUserBadges = function (badges) {
        var achievedBadges = this.achievedBadges;
        var unachievedBadges = this.unachievedBadges;

        var i;
        var name;
        var badge;
        var unachievedBadge;

        var length = badges.length;
        for (i = 0; i < length; i += 1) {
            badge = badges[i];
            name = badge.badge_key;
            unachievedBadge = unachievedBadges[name];
            if (unachievedBadge !== undefined) {
                if (!badge.current || badge.current >= unachievedBadge.totalRequired) {
                    achievedBadges[name] = unachievedBadge;
                    delete unachievedBadges[name];
                } else {
                    // Create an unachieved badge
                    unachievedBadge.currentProgress = badge.current;
                }
            }
        }

        this.isActive = true;
        this.onInitialization();
    };

    // Adds a user badge (with how much they have achieved towards it)
    GameBadges.prototype.addBadge = function (badgeName) {
        var unachievedBadge;

        if (this.isActive && !this.achievedBadges[badgeName]) {
            unachievedBadge = this.unachievedBadges[badgeName];

            if (unachievedBadge.isNonProgress) {
                this.awardBadge(badgeName);
            } else {
                unachievedBadge.addProgress();
                this.hasChanged = true;
            }
        }
    };

    // Awards a user badge no matter what the current progress
    GameBadges.prototype.awardBadge = function (badgeName) {
        if (this.isActive && !this.achievedBadges[badgeName]) {
            this.awardedBadges[badgeName] = true;
            this.hasChanged = true;
        }
    };

    // Updates the state of all badges
    GameBadges.prototype.refresh = function () {
        var that = this;
        var unachievedBadges = this.unachievedBadges;
        var awardedBadges = this.awardedBadges;
        var badgesManager = this.badgesManager;

        var badge;
        var unachievedBadge;

        function localUpdateUserBadgeProgressCallback(badge) {
            that.updateUserBadgeProgressCallback(badge);
        }

        function localAwardUserBadgeCallback(badge) {
            that.awardUserBadgeCallback(badge);
        }

        function localUpdateUserBadgeErrorCallback(msg, status, badge) {
            that.updateUserBadgeErrorCallback(msg, status, badge);
        }

        function localAwardUserBadgeErrorCallback(msg, status, badge) {
            that.awardUserBadgeErrorCallback(msg, status, badge);
        }

        for (badge in awardedBadges) {
            if (awardedBadges.hasOwnProperty(badge) && !unachievedBadges[badge].isUpdating) {
                unachievedBadges[badge].award();
                badgesManager.awardUserBadge(badge, localAwardUserBadgeCallback, localAwardUserBadgeErrorCallback);
            }
        }

        for (badge in unachievedBadges) {
            if (unachievedBadges.hasOwnProperty(badge)) {
                unachievedBadge = unachievedBadges[badge];

                if (!unachievedBadge.isUpdating && unachievedBadge.hasProgressed) {
                    unachievedBadge.onBeforeSet();

                    badgesManager.updateUserBadgeProgress(badge, unachievedBadge.currentProgress, localUpdateUserBadgeProgressCallback, localUpdateUserBadgeErrorCallback);
                }
            }
        }
    };

    // Callback when user badge has been added
    GameBadges.prototype.updateUserBadgeProgressCallback = function (badge) {
        var unachievedBadges = this.unachievedBadges;
        var badgeName = badge.badge_key;
        var unachievedBadge = unachievedBadges[badgeName];

        this.hasConnection = true;

        unachievedBadge.onSuccessfulSet(badge.current);

        // Used for writing html output on local
        this.hasChangedData = true;

        if (unachievedBadge.isAchieved()) {
            this.achievedBadges[badgeName] = unachievedBadges[badgeName];
            delete unachievedBadges[badgeName];
        }
    };

    // Error callback when user badge failed to add
    GameBadges.prototype.updateUserBadgeErrorCallback = function (msg, status, badgeData) {
        var badgeName = badgeData[0];
        var unachievedBadge = this.unachievedBadges[badgeName];

        this.hasConnection = false;
        this.hasChanged = true;

        unachievedBadge.onUnsuccessfulSet();
    };

    // Callback when user badge has been awarded
    GameBadges.prototype.awardUserBadgeCallback = function (badge) {
        var badgeName = badge.badge_key;

        this.achievedBadges[badgeName] = this.unachievedBadges[badgeName];
        delete this.unachievedBadges[badgeName];
        delete this.awardedBadges[badgeName];

        this.hasConnection = true;
        this.hasChangedData = true;
    };

    // Callback when user badge has been awarded
    GameBadges.prototype.awardUserBadgeErrorCallback = function (msg, status, badgeData) {
        var badgeName = badgeData[0];

        this.hasConnection = false;
        this.hasChanged = true;

        this.unachievedBadges[badgeName].onUnsuccessfulSet();
    };

    // Update function called in main loop
    GameBadges.prototype.update = function (currentTime) {
        var updateInterval = this.updateInterval;

        if (this.isActive && this.hasChanged) {
            if (!this.hasConnection) {
                if (currentTime - this.lastUpdateTime > updateInterval) {
                    this.updateInterval = Math.min((updateInterval * 2), this.maxInterval);
                    this.lastUpdateTime = currentTime;
                    this.refresh();
                }
            } else {
                this.lastUpdateTime = currentTime;
                this.hasChanged = false;
                this.refresh();
            }
        }
    };

    GameBadges.create = function (badgesManager, onInitialization) {
        var gameBadges = new GameBadges();

        gameBadges.badgesManager = badgesManager;
        gameBadges.onInitialization = onInitialization;

        gameBadges.unachievedBadges = {};
        gameBadges.achievedBadges = {};

        // Stores badges that will be awarded irrespective of current progress
        gameBadges.awardedBadges = {};

        gameBadges.hasChanged = true;

        // Used to know when to write html output
        gameBadges.hasChangedData = true;

        // True only if we have a leaderboard manager, and we are initialised
        gameBadges.isActive = false;

        // Vars to manage situation where connection to badges server is lost
        gameBadges.hasConnection = true;
        gameBadges.updateInterval = 1;
        gameBadges.maxInterval = 120;
        gameBadges.lastUpdateTime = TurbulenzEngine.time;

        if (badgesManager) {
            gameBadges.init();
        } else {
            onInitialization();
        }

        return gameBadges;
    };
    return GameBadges;
})();
// Copyright (c) 2011-2012 Turbulenz Limited
//
// Badge: Holds the current progress for the badge
//
var Badge = (function () {
    function Badge() {
    }
    // Update the status of the badge before updateuserbadgeprogress is called
    Badge.prototype.onBeforeSet = function () {
        this.isUpdating = true;
    };

    // After progress has been successfully set
    Badge.prototype.onSuccessfulSet = function (currentProgress) {
        this.isUpdating = false;
        if (currentProgress >= this.currentProgress) {
            this.hasProgressed = false;
        }
    };

    // After progress failed to be set
    Badge.prototype.onUnsuccessfulSet = function () {
        this.isUpdating = false;
    };

    // Adds to the progress of the badge
    Badge.prototype.addProgress = function () {
        this.currentProgress += 1;
        this.currentProgress = Math.min(this.totalRequired, this.currentProgress);
        this.hasProgressed = true;
    };

    // Updates the status of the badge before awardbadge is called
    Badge.prototype.award = function () {
        this.isUpdating = true;
    };

    // Tests if badge has been achieved
    Badge.prototype.isAchieved = function () {
        return this.currentProgress >= this.totalRequired;
    };

    Badge.create = function (totalRequired, predescription, description, title) {
        var badge = new Badge();

        if (!totalRequired) {
            badge.isNonProgress = true;
        }

        badge.currentProgress = 0;
        badge.totalRequired = totalRequired;
        badge.hasProgressed = false;

        badge.predescription = predescription;
        badge.description = description;
        badge.title = title;
        badge.isUpdating = false;

        return badge;
    };
    return Badge;
})();
// Copyright (c) 2011-2012 Turbulenz Limited
/*global TurbulenzEngine: false*/
/*global Leaderboard: false*/
//
// Leaderboard: Hold the html elements to write the leaderboard to and writes to them
//
var GameLeaderboards = (function () {
    function GameLeaderboards() {
    }
    // Adds the leadboard structure into the element div
    GameLeaderboards.prototype.init = function () {
        var that = this;
        var meta = this.leaderboardManager.meta;
        var leaderboards = this.leaderboards;
        var leaderboard;
        var spec = {
            friendsOnly: true
        };

        function initialiseLeaderboards(leaderboardArray) {
            that.initialiseLeaderboards(leaderboardArray);
        }

        function localErrorCallback(msg, status) {
            that.initialiseLeaderboardsErrorCallback(msg, status);
        }

        for (leaderboard in meta) {
            if (meta.hasOwnProperty(leaderboard)) {
                leaderboards[leaderboard] = Leaderboard.create(meta[leaderboard].sortBy, meta[leaderboard].title);
            }
        }

        // Add leaderboards to leaderboards dictionary
        this.leaderboardManager.getOverview(spec, initialiseLeaderboards, localErrorCallback);
    };

    // Error function when we fail to list & initialise badges
    GameLeaderboards.prototype.initialiseLeaderboardsErrorCallback = function (msg, status) {
        this.isActive = false;
        this.onInitialization();
    };

    // Setup the dictionary to current leaderboard states
    GameLeaderboards.prototype.initialiseLeaderboards = function (leaderboardArray) {
        var length = leaderboardArray.length;
        var leaderboards = this.leaderboards;
        var score;
        var leaderboard;
        var currentLeaderboard;
        var leaderboardName;

        var i;

        for (i = 0; i < length; i += 1) {
            leaderboard = leaderboardArray[i];
            score = leaderboard.score;
            leaderboardName = leaderboard.key;
            currentLeaderboard = leaderboards[leaderboardName];

            currentLeaderboard.currentScore = score;
            currentLeaderboard.newScore = score;
        }

        this.isActive = true;
        this.onInitialization();
    };

    // Updates the specified leaderboard with the new score provided
    GameLeaderboards.prototype.setScore = function (leaderboardName, score) {
        if (this.isActive) {
            var leaderboard = this.leaderboards[leaderboardName];
            leaderboard.setScore(score);
            this.hasChanged = true;
        }
    };

    // Sends all new leaderboard data
    GameLeaderboards.prototype.refresh = function () {
        var leaderboards = this.leaderboards;

        var leaderboardName;
        var leaderboard;

        for (leaderboardName in leaderboards) {
            if (leaderboards.hasOwnProperty(leaderboardName)) {
                leaderboard = leaderboards[leaderboardName];

                if (!leaderboard.isUpdating && leaderboard.hasImprovedScore()) {
                    leaderboard.onBeforeSet();
                    this.refreshLeaderboard(leaderboardName, leaderboard.newScore);
                }
            }
        }
    };

    // Sends the data for the specified leaderboard
    GameLeaderboards.prototype.refreshLeaderboard = function (leaderboardName, score) {
        var that = this;

        function localSetLeaderboardCallback(key, score, newBest, bestScore) {
            that.setLeaderboardCallback(key, score, newBest, bestScore);
        }

        function localSetErrorCallback(msg, status, setFunction, leaderboard) {
            that.setErrorCallback(msg, status, setFunction, leaderboard);
        }

        this.leaderboardManager.set(leaderboardName, score, localSetLeaderboardCallback, localSetErrorCallback);
    };

    // Update function called in main loop
    GameLeaderboards.prototype.update = function (currentTime) {
        var updateInterval = this.updateInterval;

        if (this.isActive && this.hasChanged) {
            if (!this.hasConnection) {
                if (currentTime - this.lastUpdateTime > updateInterval) {
                    this.updateInterval = Math.min((updateInterval * 2), this.maxInterval);
                    this.lastUpdateTime = currentTime;
                    this.refresh();
                }
            } else {
                this.hasChanged = false;
                this.lastUpdateTime = currentTime;
                this.refresh();
            }
        }
    };

    // After successful leaderboard set
    GameLeaderboards.prototype.setLeaderboardCallback = function (key, score, newBest, bestScore) {
        var leaderboard = this.leaderboards[key];
        leaderboard.onSuccessfulSet(bestScore);

        // Used for writing html output on local
        this.hasChangedData = true;

        this.hasConnection = true;
    };

    // Error callback - uses window alert
    GameLeaderboards.prototype.setErrorCallback = function (msg, status, setFunction, leaderboardData) {
        var leaderboardName = leaderboardData[0];
        var leaderboard = this.leaderboards[leaderboardName];

        leaderboard.onUnsuccessfulSet();

        this.hasConnection = false;
        this.hasChanged = true;
    };

    GameLeaderboards.create = function (leaderboardManager, onInitialization) {
        var gameLeaderboards = new GameLeaderboards();

        gameLeaderboards.leaderboardManager = leaderboardManager;
        gameLeaderboards.onInitialization = onInitialization;

        gameLeaderboards.leaderboards = {};
        gameLeaderboards.hasChanged = true;

        // Used to know when to write html output
        gameLeaderboards.hasChangedData = true;

        // True only if we have a leaderboard manager, and we are initialised
        gameLeaderboards.isActive = false;

        // Vars to manage situation where connection to badges server is lost
        gameLeaderboards.hasConnection = true;
        gameLeaderboards.updateInterval = 1;
        gameLeaderboards.maxInterval = 120;
        gameLeaderboards.lastUpdateTime = TurbulenzEngine.time;

        if (leaderboardManager) {
            gameLeaderboards.init();
        } else {
            onInitialization();
        }

        return gameLeaderboards;
    };
    return GameLeaderboards;
})();
// Copyright (c) 2011-2012 Turbulenz Limited
//
// HtmlWriter: Class to format and write badge and leaderboard data to page
//
var HtmlWriter = (function () {
    function HtmlWriter() {
    }
    HtmlWriter.prototype.init = function (scoreDivID, killsDivID, leaderboardDivId, badgesDivId) {
        var unachievedBadges = this.badges.unachievedBadges;
        var achievedBadges = this.badges.achievedBadges;
        var unachievedBadge;
        var achievedBadge;
        var leaderboards = this.leaderboards.leaderboards;
        var leaderboardDivs = this.leaderboardDivs;
        var badgeDivs = this.badgeDivs;

        var leaderboardDiv;
        var badgeDiv;
        var leaderboard;
        var badge;

        this.scoreDiv = document.getElementById(scoreDivID);
        this.killsDiv = document.getElementById(killsDivID);
        this.leaderboardsDiv = document.getElementById(leaderboardDivId);
        this.badgesDiv = document.getElementById(badgesDivId);

        if (!this.scoreDiv || !this.killsDiv || !this.leaderboardsDiv || !this.badgesDiv) {
            this.runAsTzjs = true;
            return;
        }

        for (leaderboard in leaderboards) {
            if (leaderboards.hasOwnProperty(leaderboard)) {
                leaderboardDiv = {
                    name: leaderboard,
                    title: leaderboards[leaderboard].title,
                    dataDiv: null
                };

                leaderboardDivs[leaderboard] = leaderboardDiv;

                this.addLeaderboardHtml(leaderboardDiv);
            }
        }

        for (badge in achievedBadges) {
            if (achievedBadges.hasOwnProperty(badge)) {
                achievedBadge = achievedBadges[badge];

                badgeDiv = {
                    name: badge,
                    title: achievedBadge.title,
                    dataDiv: null
                };
                badgeDivs[badge] = badgeDiv;

                this.addBadgeHtml(badgeDiv);
            }
        }

        for (badge in unachievedBadges) {
            if (unachievedBadges.hasOwnProperty(badge)) {
                unachievedBadge = unachievedBadges[badge];

                badgeDiv = {
                    name: badge,
                    title: unachievedBadge.title,
                    dataDiv: null
                };
                badgeDivs[badge] = badgeDiv;

                this.addBadgeHtml(badgeDiv);
            }
        }
    };

    // Creates html structure for a leaderboard
    HtmlWriter.prototype.addLeaderboardHtml = function (leaderboard) {
        var leaderboardName = leaderboard.name;
        var leaderboardTitle = leaderboard.title;
        var leaderboardNameDiv;
        var leaderboardDiv;
        var leaderboardDataDiv;
        var leaderboardsDiv = this.leaderboardsDiv;

        // Adds the div
        leaderboardDiv = document.createElement("div");
        leaderboardDiv.id = leaderboardName;
        leaderboardDiv.className = "leaderboard-block";
        leaderboardsDiv.appendChild(leaderboardDiv);

        // Adds the title div
        leaderboardNameDiv = document.createElement("div");
        leaderboardNameDiv.className = "leaderboard-block-title";
        this.writeTextContent(leaderboardNameDiv, leaderboardTitle);
        leaderboardDiv.appendChild(leaderboardNameDiv);

        // Adds the leaderboard content div
        leaderboardDataDiv = document.createElement("span");
        leaderboard.dataDiv = leaderboardDataDiv;
        leaderboardDataDiv.id = (leaderboardName + "_data");
        leaderboardDataDiv.className = "leaderboard-block-score";
        leaderboardDiv.appendChild(leaderboardDataDiv);
    };

    // Creates html structure for a badge
    HtmlWriter.prototype.addBadgeHtml = function (badge) {
        var badgeName = badge.name;
        var badgeTitle = badge.title;
        var badgeDiv;
        var badgeWrapperDiv;
        var badgeNameDiv;
        var badgeDescriptionDiv;
        var badgeDataDiv;
        var badgesDiv = this.badgesDiv;

        // Adds the div
        badgeDiv = document.createElement("div");
        badgeDiv.id = badgeName;
        badgeDiv.className = "badge-block";
        badgesDiv.appendChild(badgeDiv);

        // Add the title + description wrapper
        badgeWrapperDiv = document.createElement("div");
        badgeWrapperDiv.className = "badge-block-wrapper";
        badgeDiv.appendChild(badgeWrapperDiv);

        // Add the title div
        badgeNameDiv = document.createElement("div");
        badgeNameDiv.className = "badge-block-title";
        this.writeTextContent(badgeNameDiv, badgeTitle);
        badgeWrapperDiv.appendChild(badgeNameDiv);

        // Add the description div
        badgeDescriptionDiv = document.createElement("div");
        badge.descriptionDiv = badgeDescriptionDiv;
        badgeDescriptionDiv.id = badgeName + "_description";
        badgeDescriptionDiv.className = "badge-block-description";
        badgeWrapperDiv.appendChild(badgeDescriptionDiv);

        // Adds the badge content div
        badgeDataDiv = document.createElement("div");
        badge.dataDiv = badgeDataDiv;
        badgeDataDiv.id = badgeName + "_data";
        badgeDataDiv.className = "badge-block-progress";
        badgeDiv.appendChild(badgeDataDiv);
    };

    // (Re)Writes the current score html
    HtmlWriter.prototype.writeScore = function () {
        this.writeTextContent(this.scoreDiv, this.game.score);
        this.writeTextContent(this.killsDiv, this.game.kills);
    };

    // (Re)Writes all leaderboards html
    HtmlWriter.prototype.writeLeaderboards = function () {
        var leaderboards = this.leaderboards.leaderboards;

        var leaderboard;

        for (leaderboard in leaderboards) {
            if (leaderboards.hasOwnProperty(leaderboard)) {
                this.writeLeaderboard(leaderboard);
            }
        }
    };

    // (Re)Writes all badges html
    HtmlWriter.prototype.writeBadges = function () {
        var achievedBadges = this.badges.achievedBadges;
        var unachievedBadges = this.badges.unachievedBadges;

        var badge;

        for (badge in achievedBadges) {
            if (achievedBadges.hasOwnProperty(badge)) {
                this.writeAchievedBadge(badge);
            }
        }

        for (badge in unachievedBadges) {
            if (unachievedBadges.hasOwnProperty(badge)) {
                this.writeUnachievedBadge(badge);
            }
        }
    };

    // (Re)Write leaderboard html content
    HtmlWriter.prototype.writeLeaderboard = function (leaderboardName) {
        var leaderboard = this.leaderboards.leaderboards[leaderboardName];
        var leaderboardString = "";

        if (leaderboard.currentScore) {
            leaderboardString += leaderboard.currentScore.toFixed(0);
        } else {
            leaderboardString += "None set";
        }

        this.writeTextContent(this.leaderboardDivs[leaderboardName]['dataDiv'], leaderboardString);
    };

    // (Re)Write achieved badge html content
    HtmlWriter.prototype.writeAchievedBadge = function (badgeName) {
        var badge = this.badges.achievedBadges[badgeName];
        var badgeDiv = this.badgeDivs[badgeName];
        var badgeString = "";

        badgeString += "Got this!";

        this.writeTextContent(badgeDiv['dataDiv'], badgeString);
        this.writeTextContent(badgeDiv['descriptionDiv'], badge.description);
    };

    // (Re)Write unachieved badge html content
    HtmlWriter.prototype.writeUnachievedBadge = function (badgeName) {
        var badge = this.badges.unachievedBadges[badgeName];
        var badgeDiv = this.badgeDivs[badgeName];
        var badgeProgress = badge.currentProgress ? badge.currentProgress.toString(10) : "0";
        var badgeString = "";
        var badgeDescription = badge.predescription || badge.description;

        if (badge.totalRequired) {
            badgeString += badgeProgress;
            badgeString += "/";
            badgeString += badge.totalRequired.toString(10);
        } else {
            badgeString += "Unachieved";
        }

        this.writeTextContent(badgeDiv['dataDiv'], badgeString);
        this.writeTextContent(badgeDiv['descriptionDiv'], badgeDescription);
    };

    // Writes text to element specified
    HtmlWriter.prototype.writeTextContent = function (element, value) {
        var content;

        if (!element) {
            return;
        }

        content = element.textContent;

        if (value !== undefined) {
            if (content !== undefined) {
                element.textContent = value;
            } else {
                element.innerText = value;
            }
        }
    };

    // Refreshed the score/leaderboards/badges html content if necessary
    HtmlWriter.prototype.update = function () {
        if (this.runAsTzjs) {
            return;
        }

        var leaderboards = this.leaderboards;
        var badges = this.badges;
        var game = this.game;

        if (leaderboards.hasChangedData) {
            leaderboards.hasChangedData = false;
            this.writeLeaderboards();
        }

        if (badges.hasChangedData) {
            badges.hasChangedData = false;
            this.writeBadges();
        }

        if (game.hasChangedScore) {
            game.hasChangedScore = false;
            this.writeScore();
        }
    };

    HtmlWriter.create = function (leaderboards, badges, game) {
        var htmlWriter = new HtmlWriter();

        htmlWriter.leaderboards = leaderboards;
        htmlWriter.badges = badges;
        htmlWriter.game = game;

        htmlWriter.leaderboardsDiv = null;
        htmlWriter.badgesDiv = null;
        htmlWriter.scoreDiv = null;
        htmlWriter.killsDiv = null;

        htmlWriter.leaderboardDivs = {};
        htmlWriter.badgeDivs = {};

        // To avoid writing to the page if run as tzjs
        htmlWriter.runAsTzjs = false;

        htmlWriter.init("scores", "kills", "leaderboards", "badges");

        return htmlWriter;
    };
    return HtmlWriter;
})();
// Copyright (c) 2011-2012 Turbulenz Limited
//
// Leaderboard: Holds the current score, new score and set score logic for a leaderboard
//
var Leaderboard = (function () {
    function Leaderboard() {
    }
    // Before the leaderboard set call is made
    Leaderboard.prototype.onBeforeSet = function () {
        this.isUpdating = true;
    };

    // When leaderboard set callback is made
    Leaderboard.prototype.onSuccessfulSet = function (bestScore) {
        this.currentScore = bestScore;
        this.isUpdating = false;
    };

    // When leaderboard set failed
    Leaderboard.prototype.onUnsuccessfulSet = function () {
        this.isUpdating = false;
    };

    Leaderboard.create = function (sortBy, title) {
        var leaderboard = new Leaderboard();

        leaderboard.sortBy = sortBy;
        leaderboard.title = title;
        leaderboard.isUpdating = false;

        function setHighScore(score) {
            if (score > this.newScore) {
                this.newScore = score;
            }
        }

        function setLowScore(score) {
            if (score < this.newScore || !this.newScore) {
                this.newScore = score;
            }
        }

        function hasImprovedHighScore() {
            if (this.newScore > this.currentScore) {
                return true;
            }

            return false;
        }

        function hasImprovedLowScore() {
            if (this.newScore < this.currentScore || this.newScore && !this.currentScore) {
                return true;
            }

            return false;
        }

        if (sortBy > 0) {
            leaderboard.currentScore = 0;
            leaderboard.newScore = 0;
            leaderboard.setScore = setHighScore;
            leaderboard.hasImprovedScore = hasImprovedHighScore;
        } else {
            leaderboard.currentScore = null;
            leaderboard.newScore = null;
            leaderboard.setScore = setLowScore;
            leaderboard.hasImprovedScore = hasImprovedLowScore;
        }

        return leaderboard;
    };
    return Leaderboard;
})();
// Copyright (c) 2011-2012 Turbulenz Limited
/*jshint nomen: false*/
/*global TurbulenzBridge: false*/
/*exported TurbulenzUI*/
// requires jQuery
/**
* The DynamicUI manager sends events to the DynamicUI server to create instances of UI elements on the host website. It
* then manages updates to the UI either responding to requests for the value for a specific UI element, or pushing
* values to elements referenced by id.
*/
var DynamicUIManager = (function () {
    function DynamicUIManager() {
    }
    /**
    * Helper function to add a new UI element. Sends an event to the dynamicUI server and sets up listeners to
    * handle requests to get and set the value that come form the UI.
    *
    * @param {String} type The type of the UI element used
    * @param {String} title The title to use for the UI element
    * @param {Function} getValue A callback that gets the value for the UI element
    * @param {Function} setValue A callback that is called when the value in the UI is changed
    * @param [groupId] The group id of the parent group. If not defined then the default group is used
    * @param [options] An object containing UI specific options. The details of this will depend on the implementation
    * of the DynamicUI server
    * @returns The id of the new element to use to push values to this UI element
    */
    DynamicUIManager.prototype._addUI = function (type, title, getValue, setValue, groupId, options) {
        var id = this._newId();
        TurbulenzBridge.emit('dynamicui.add-item', JSON.stringify({
            id: id,
            type: type,
            title: title,
            groupId: groupId || null,
            options: options || {}
        }));

        this._setters[id] = setValue;
        this._getters[id] = getValue;
        return id;
    };

    /**
    * Utility function to handle "watch stashed object" events.
    *
    * @param paramstring The JSONified request
    */
    DynamicUIManager.prototype._watchStashedObject = function (paramstring) {
        var params = JSON.parse(paramstring);
        var id = params.id;
        var property = params.property;
        var title = params.title || id;
        var ui = params.ui;
        var options = params.options || {};
        var groupId = params.groupId || this._watchGroup;
        this.watchVariable(title, this._objects[id], property, ui, groupId, options);
    };

    /**
    * Adds a slider to the specified group.
    *
    * @param {String} title The title to use for the UI element
    * @param {Function} getValue A callback that gets the value for the UI element
    * @param {Function} setValue A callback that is called when the value in the UI is changed
    * @param [groupId] The group id of the parent group. If not defined then the default group is used
    * @param [options] An object containing UI specific options. The details of this will depend on the implementation
    * of the DynamicUI server
    * @returns The id of the new element to use to push values to this UI element
    */
    DynamicUIManager.prototype.addSlider = function (title, getValue, setValue, groupId, options) {
        return this._addUI('slider', title, getValue, setValue, groupId, options);
    };

    /**
    * Adds a checkbox to the specified group.
    *
    * @param {String} title The title to use for the UI element
    * @param {Function} getValue A callback that gets the value for the UI element
    * @param {Function} setValue A callback that is called when the value in the UI is changed
    * @param [groupId] The group id of the parent group. If not defined then the default group is used
    * @param [options] An object containing UI specific options. The details of this will depend on the implementation
    * of the DynamicUI server
    * @returns The id of the new element to use to push values to this UI element
    */
    DynamicUIManager.prototype.addCheckbox = function (title, getValue, setValue, groupId, options) {
        return this._addUI('checkbox', title, getValue, setValue, groupId, options);
    };

    /**
    * Adds a drop-down selector to the specified group.
    *
    * @param {String} title The title to use for the UI element
    * @param {Function} getValue A callback that gets the value for the UI element
    * @param {Function} setValue A callback that is called when the value in the UI is changed
    * @param [groupId] The group id of the parent group. If not defined then the default group is used
    * @param [options] An object containing UI specific options. The details of this will depend on the implementation
    * of the DynamicUI server
    * @returns The id of the new element to use to push values to this UI element
    */
    DynamicUIManager.prototype.addSelect = function (title, getValue, setValue, groupId, options) {
        return this._addUI('select', title, getValue, setValue, groupId, options);
    };

    /**
    * Adds an updatable label to the specified group.
    *
    * @param {String} title The title to use for the UI element
    * @param {Function} getValue A callback that gets the value for the UI element
    * @param {Function} setValue A callback that is called when the value in the UI is changed
    * @param [groupId] The group id of the parent group. If not defined then the default group is used
    * @param [options] An object containing UI specific options. The details of this will depend on the implementation
    * of the DynamicUI server
    * @returns The id of the new element to use to push values to this UI element
    */
    DynamicUIManager.prototype.addWatch = function (title, getValue, setValue, groupId, options) {
        return this._addUI('watch', title, getValue, setValue, groupId, options);
    };

    /**
    * Adds a set of radio buttons to the specified group.
    *
    * @param {String} title The title to use for the UI element
    * @param {Function} getValue A callback that gets the value for the UI element
    * @param {Function} setValue A callback that is called when the value in the UI is changed
    * @param [groupId] The group id of the parent group. If not defined then the default group is used
    * @param [options] An object containing UI specific options. The details of this will depend on the implementation
    * of the DynamicUI server
    * @returns The id of the new element to use to push values to this UI element
    */
    DynamicUIManager.prototype.addRadioButton = function (title, getValue, setValue, groupId, options) {
        return this._addUI('radio', title, getValue, setValue, groupId, options);
    };

    /**
    * Destroys the specified UI element.
    *
    * @param id The Id of the element to destroy. If the element is a group, the group and all its children are
    * destroyed
    */
    DynamicUIManager.prototype.destroy = function (id) {
        TurbulenzBridge.emit('dynamicui.destroy', JSON.stringify({
            id: id
        }));
    };

    /**
    * Updates the specified UI element with a new value.
    *
    * @param id The Id of the element to update
    * @param value The value to send to the UI
    */
    DynamicUIManager.prototype.pushValue = function (id, value) {
        TurbulenzBridge.emit('dynamicui.pushvalue', JSON.stringify({
            id: id,
            value: value
        }));
    };

    /**
    * Adds a group to the dynamid UI.
    *
    * @param {String} title The title of the group
    * @param groupId The parent group to add this new group to
    * @returns The id of the newly created group.
    */
    DynamicUIManager.prototype.addGroup = function (title, groupId) {
        var id = this._newId();
        TurbulenzBridge.emit('dynamicui.group-create', JSON.stringify({
            id: id,
            title: title,
            groupId: groupId || null
        }));
        return id;
    };

    /**
    * Adds a UI element to an existing group. The element is moved, so if it is already a member of a group it
    * will be removed from that group and added to the group specified in the function call.
    *
    * @param id The id of the element to move
    * @param groupId The parent group to add this new group to
    */
    DynamicUIManager.prototype.addToGroup = function (id, groupId) {
        TurbulenzBridge.emit('dynamicui.group-add', JSON.stringify({
            id: id,
            groupId: groupId
        }));
    };

    /**
    * Removes a UI element from a group. This does not destroy the UI element so it can be used to temporarily hide
    * a UI element which can then be re-shown by calling addToGroup
    *
    * @param id The id of the UI element to remove
    * @param groupId The id of the group to remove it from
    */
    DynamicUIManager.prototype.removeFromGroup = function (id, groupId) {
        TurbulenzBridge.emit('dynamicui.group-remove', JSON.stringify({
            id: id,
            groupId: groupId
        }));
    };

    /**
    * Helper function to watch the specified property of an object. This automatically sets up the getter and setter
    * callbacks on the property to tie it to the state of the UI.
    *
    * @param {String} title The title of the UI element to create
    * @param {Object} object The object whose property will be watched
    * @param {String} property The name of the property to watch
    * @param {String} [ui = "watch"] The UI to use to show the variable
    * @param [group] The group to add this watch element to
    * @param [options] The UI creation options to use
    * @returns The id of the newly created element
    */
    DynamicUIManager.prototype.watchVariable = function (title, object, property, ui, group, options) {
        var uiType = ui || 'watch';
        var groupId = group || null;
        var id = -1;

        var getVal = function getValFn() {
            if (property) {
                return object[property];
            } else {
                return object;
            }
        };

        var setVal = function setValFn(value) {
            object[property] = value;
        };

        switch (uiType) {
            case 'slider':
                id = this.addSlider(title, getVal, setVal, groupId, options);
                break;
            case 'checkbox':
                id = this.addCheckbox(title, getVal, setVal, groupId, options);
                break;
            case 'radio':
                id = this.addRadioButton(title, getVal, setVal, groupId, options);
                break;
            case 'select':
                id = this.addSelect(title, getVal, setVal, groupId, options);
                break;
            case 'watch':
                id = this.addWatch(title, getVal, setVal, groupId, options);
                break;
        }

        return id;
    };

    DynamicUIManager.prototype.showObject = function (title, object, editable, group) {
        var objectGroup = this.addGroup(title, group);
        var propertyName, property;
        for (propertyName in object) {
            if (object.hasOwnProperty(propertyName)) {
                property = object[propertyName];
                if (typeof property === "object") {
                    this.showObject(propertyName, property, editable, objectGroup);
                } else {
                    if (editable) {
                        // TODO: parse type and provide appropriate UI
                        this.watchVariable(propertyName, object, propertyName, 'watch', objectGroup);
                    } else {
                        this.watchVariable(propertyName, object, propertyName, 'watch', objectGroup);
                    }
                }
            }
        }
        return objectGroup;
    };

    /**
    * Registers a named path to an object so that the object can be referenced from another context for the creation of
    * watch UI
    *
    * @param {Object} object The object to stash
    * @param {String} path The path to use to access the object in the form "folder/folder/folder/item", for example
    * "actors/npcs/enemies/bots/ed209"
    * @returns The id of the stashed object - currently for internal use only
    */
    DynamicUIManager.prototype.stashObject = function (object, path) {
        var id = this._newId();
        this._objects[id] = object;
        TurbulenzBridge.emit('dynamicui.stash-add', id + ':' + path);
        return id;
    };

    DynamicUIManager.create = /**
    * Creates a DynamicUI manager and initialises it, registering against events.
    *
    * @param title
    * @returns {DynamicUIManager} The UI Manager
    */
    function (/* title */ ) {
        var uiMan = new DynamicUIManager();
        uiMan._objects = {};
        uiMan._setters = {};
        uiMan._getters = {};

        uiMan._watchGroup = uiMan.addGroup('watches');

        // Watch for calls from the console to watch stashed objects
        TurbulenzBridge.setListener('dynamicui.stash-watch', function (paramstring) {
            uiMan._watchStashedObject(paramstring);
        });

        TurbulenzBridge.setListener('dynamicui.changevalue', function (jsonstring) {
            var options = JSON.parse(jsonstring);
            var setter = uiMan._setters[options.id];
            if (setter) {
                setter(options.value);
            }
        });

        TurbulenzBridge.setListener('dynamicui.requestvalue', function (jsonstring) {
            var options = JSON.parse(jsonstring);
            var getter = uiMan._getters[options.id];
            if (getter) {
                TurbulenzBridge.emit('dynamicui.pushvalue', JSON.stringify({
                    id: options.id,
                    value: getter()
                }));
            }
        });

        return uiMan;
    };
    return DynamicUIManager;
})();

DynamicUIManager.prototype._newId = ((function () {
    var id = 0;

    return function getId() {
        id += 1;
        return id;
    };
})());

/**
* The instance of the DynamicUI manager
*/
var TurbulenzUI = DynamicUIManager.create();
// Copyright (c) 2011-2012 Turbulenz Limited
;

;

;

//
// Game: Contains game logic for worm game
//
var Game = (function () {
    function Game() {
    }
    // Update game
    Game.prototype.update = function (isHost, moveWorms) {
        (this.gameStates[this.currentState]).call(this, isHost, moveWorms);
    };

    // Check collisions between worms and foods
    Game.prototype.checkCollisions = function () {
        var maxPlayers = this.gameSettings.maxPlayers;
        var myWormIndex = this.myWormIndex;
        var worms = this.worms;
        var i, j, worm;

        for (i = 0; i < maxPlayers; i += 1) {
            worm = worms[i];

            if (worm.killedBy !== null) {
                worm.killedBy = null;

                this.placeWorm(i);
            }
        }

        for (i = 0; i < maxPlayers; i += 1) {
            worm = worms[i];

            if (worm.isIntersectingSelf()) {
                worm.die(i);

                if (i === myWormIndex) {
                    this.died(i);
                }
            } else {
                for (j = 0; j < maxPlayers; j += 1) {
                    if (i !== j) {
                        if (worm.isIntersecting(worms[j])) {
                            worm.die(j);

                            if (i === myWormIndex) {
                                this.died(j);
                            } else if (j === myWormIndex) {
                                this.kill();
                            }

                            break;
                        }
                    }
                }
            }
        }

        // Check foods
        var foods = this.foods;
        for (i = 0; i < maxPlayers; i += 1) {
            var food = foods[i];
            var foodX = food.x;
            var foodY = food.y;

            food.eatenBy = null;

            for (j = 0; j < maxPlayers; j += 1) {
                worm = worms[j];
                if (worm.containsPosition(foodX, foodY)) {
                    food.eatenBy = j;

                    if (j === myWormIndex) {
                        worm.addToTail();
                        this.scored();
                    }

                    this.placeFood(i);

                    break;
                }
            }
        }
    };

    // Update loop whilst playing
    Game.prototype.play = function (isHost, moveWorms) {
        var myWormIndex = this.myWormIndex;
        if (myWormIndex < 0) {
            return;
        }

        this.badges.addBadge("played");

        if (moveWorms) {
            var worm = this.worms[myWormIndex];

            var newWormDirection = this.newWormDirection;
            if (newWormDirection !== null) {
                worm.changeDirection(newWormDirection);
                this.newWormDirection = null;
            }

            worm.update();
            if (worm.hasLooped) {
                this.badges.addBadge("driven_over_the_edge");
            }
        }

        if (isHost) {
            this.checkCollisions();
        }
    };

    // Update loop whilst dead
    Game.prototype.dead = function (isHost/*, moveWorms */ ) {
        if (isHost) {
            this.checkCollisions();
        }
    };

    // My worm eat food
    Game.prototype.scored = function () {
        this.score += this.scoreIncrement;
        this.gameSession.setStatus('Playing score ' + this.score);
        this.hasChangedScore = true;
        this.badges.addBadge("abstract_cube_devourer");
        this.worms[this.myWormIndex].playerInfo.score = this.score;
    };

    // Someone collided against me
    Game.prototype.kill = function () {
        this.kills += 1;
        this.hasChangedScore = true;
        this.badges.addBadge("abstract_killer");
    };

    // My worm died
    Game.prototype.died = function (killedBy) {
        this.currentState = this.state.DEAD;
        this.gameSession.setStatus('Died with score ' + this.score);
        var badges = this.badges;
        var leaderboards = this.leaderboards;

        badges.addBadge("addicted");

        if (this.myWormIndex === killedBy) {
            badges.addBadge("suicidal_worm");
        }

        leaderboards.setScore("best-score", this.score);
        leaderboards.setScore("most-kills", this.kills);
    };

    // Randomly places food into a free space
    Game.prototype.placeFood = function (foodIndex) {
        var gameSettings = this.gameSettings;
        var xScaler = gameSettings.width;
        var yScaler = gameSettings.height;
        var maxPlayers = gameSettings.maxPlayers;

        var worms = this.worms;
        var foods = this.foods;

        var floor = Math.floor;
        var random = Math.random;
        var randomX;
        var randomY;
        var n, food;
        do {
            randomX = floor(random() * xScaler);
            randomY = floor(random() * yScaler);

            for (n = 0; n < maxPlayers; n += 1) {
                if (worms[n].containsPosition(randomX, randomY)) {
                    break;
                }

                if (n !== foodIndex) {
                    food = foods[n];
                    if (food.x === randomX && food.y === randomY) {
                        break;
                    }
                }
            }
        } while(n < maxPlayers);

        food = foods[foodIndex];
        food.x = randomX;
        food.y = randomY;
        food.updated = true;
    };

    // Randomly places worm into a free space
    Game.prototype.placeWorm = function (wormIndex) {
        var gameSettings = this.gameSettings;
        var xScaler = gameSettings.width;
        var yScaler = gameSettings.height;
        var maxPlayers = gameSettings.maxPlayers;

        var worms = this.worms;
        var foods = this.foods;

        var floor = Math.floor;
        var random = Math.random;
        var randomX;
        var randomY;
        var n, food;
        do {
            randomX = floor(random() * xScaler);
            randomY = floor(random() * yScaler);

            for (n = 0; n < maxPlayers; n += 1) {
                if (n !== wormIndex) {
                    if (worms[n].containsPosition(randomX, randomY)) {
                        break;
                    }
                }

                // Check foods
                food = foods[n];
                if (food.x === randomX && food.y === randomY) {
                    break;
                }
            }
        } while(n < maxPlayers);

        worms[wormIndex].reset(randomX, randomY);
    };

    // serialize the whole game
    Game.prototype.serialize = function (data) {
        var gameSettings = this.gameSettings;
        var maxPlayers = gameSettings.maxPlayers;

        var worms = this.worms;
        var foods = this.foods;

        var wormsState = [];
        var foodsState = [];

        var n;
        for (n = 0; n < maxPlayers; n += 1) {
            wormsState[n] = worms[n].serialize();

            var food = foods[n];

            foodsState[n] = {
                x: food.x,
                y: food.y
            };

            var eatenBy = food.eatenBy;
            if (eatenBy !== null) {
                foodsState[n].eatenBy = eatenBy;
            }
        }

        data.worms = wormsState;
        data.foods = foodsState;
    };

    // serialize delta information
    Game.prototype.serializeDelta = function (isHost, delta) {
        var gameSettings = this.gameSettings;
        var maxPlayers = gameSettings.maxPlayers;

        var updated = false;

        var myWormIndex = this.myWormIndex;
        var worms = this.worms;
        var wormsState = [];
        var worm;

        if (isHost) {
            var foods = this.foods;
            var foodsState = [];

            var n;
            for (n = 0; n < maxPlayers; n += 1) {
                worm = worms[n];
                if (worm.updated) {
                    worm.updated = false;
                    wormsState[n] = worm.serialize();
                }

                var food = foods[n];
                if (food.updated) {
                    food.updated = false;

                    foodsState[n] = {
                        x: food.x,
                        y: food.y
                    };

                    var eatenBy = food.eatenBy;
                    if (eatenBy !== null) {
                        foodsState[n].eatenBy = eatenBy;
                    }
                }
            }

            if (0 < foodsState.length) {
                delta.foods = foodsState;
                updated = true;
            }
        } else {
            worm = worms[myWormIndex];
            if (worm && worm.updated) {
                worm.updated = false;
                wormsState[myWormIndex] = worm.serialize();
            }
        }

        if (0 < wormsState.length) {
            delta.worms = wormsState;
            updated = true;
        }

        return updated;
    };

    // Deserialize game
    Game.prototype.deserialize = function (isHost, data) {
        var myWormIndex = this.myWormIndex;
        var worms = this.worms;
        var foods = this.foods;

        var updated = false;
        var numStates, n;

        var wormsState = data.worms;
        if (wormsState !== undefined) {
            numStates = wormsState.length;
            for (n = 0; n < numStates; n += 1) {
                var wormState = wormsState[n];
                if (wormState) {
                    updated = true;

                    var worm = worms[n];

                    worm.deserialize(isHost, wormState);

                    if (!isHost) {
                        var killedBy = worm.killedBy;
                        if (killedBy !== null) {
                            worm.killedBy = null;

                            if (n === myWormIndex) {
                                this.died(killedBy);
                            } else if (killedBy === myWormIndex) {
                                this.kill();
                            }
                        }
                    }
                }
            }
        }

        if (!isHost) {
            var foodsState = data.foods;
            if (foodsState !== undefined) {
                numStates = foodsState.length;
                for (n = 0; n < numStates; n += 1) {
                    var foodState = foodsState[n];
                    if (foodState) {
                        updated = true;

                        var x = foodState.x;
                        var y = foodState.y;

                        var food = foods[n];

                        food.x = x;
                        food.y = y;

                        if (foodState.eatenBy === myWormIndex) {
                            worms[myWormIndex].addToTail();
                            this.scored();
                        }
                    }
                }
            }
        }

        return updated;
    };

    // Returns the requested food
    Game.prototype.getFood = function (foodIndex) {
        return this.foods[foodIndex];
    };

    // Returns the requested worm
    Game.prototype.getWorm = function (wormIndex) {
        return this.worms[wormIndex];
    };

    // Resets the worm, and food
    Game.prototype.reset = function () {
        this.currentState = this.state.PLAY;
        this.gameSession.setStatus('Playing');
        this.score = 0;
        this.kills = 0;
        this.hasChangedScore = true;
        this.startTime = TurbulenzEngine.time;
    };

    // Start the game
    Game.prototype.start = function () {
        var maxPlayers = this.gameSettings.maxPlayers;
        var n;

        this.myWormIndex = 0;

        for (n = 0; n < maxPlayers; n += 1) {
            this.placeWorm(n);
        }

        for (n = 0; n < maxPlayers; n += 1) {
            this.placeFood(n);
        }
    };

    // Handles input
    Game.prototype.onKeyDown = function (keynum) {
        var myWormIndex = this.myWormIndex;

        if (this.currentState === this.state.ERROR && keynum === this.keyCodes.SPACE) {
            delete this.join_error;
            this.join_error_cb();
            return;
        }

        if (myWormIndex < 0) {
            return;
        }

        var worm = this.worms[myWormIndex];
        var direction = worm.direction;

        var keyCodes = this.keyCodes;

        switch (keynum) {
            case keyCodes.A:
            case keyCodes.LEFT:
            case keyCodes.NUMPAD_4:
                this.newWormDirection = direction.LEFT;
                break;

            case keyCodes.D:
            case keyCodes.RIGHT:
            case keyCodes.NUMPAD_6:
                this.newWormDirection = direction.RIGHT;
                break;

            case keyCodes.W:
            case keyCodes.UP:
            case keyCodes.NUMPAD_8:
                this.newWormDirection = direction.UP;
                break;

            case keyCodes.S:
            case keyCodes.DOWN:
            case keyCodes.NUMPAD_2:
                this.newWormDirection = direction.DOWN;
                break;
            case keyCodes.T:
                worm.playerInfo.team = "Snakes";
                break;
            case keyCodes.Y:
                worm.playerInfo.team = "Worms";
                break;
            case keyCodes.G:
                worm.playerInfo.color = "green";
                break;
            case keyCodes.H:
                worm.playerInfo.color = "yellow";
                break;

            case keyCodes.SPACE:
                if (this.currentState === this.state.DEAD) {
                    this.reset();
                }
                break;

            case keyCodes.RETURN:
                this.graphicsDevice.fullscreen = !this.graphicsDevice.fullscreen;
                break;
        }
    };

    // Handles mouse input
    Game.prototype.onMouseDown = function (button) {
        var myWormIndex = this.myWormIndex;
        if (myWormIndex < 0) {
            return;
        }

        var worm = this.worms[myWormIndex];
        var directionVector = worm.directionVector;
        var direction = worm.direction;

        var mouseCodes = this.mouseCodes;

        if (mouseCodes.BUTTON_0 === button) {
            switch (directionVector) {
                case worm.upVector:
                    this.newWormDirection = direction.LEFT;
                    break;
                case worm.downVector:
                    this.newWormDirection = direction.RIGHT;
                    break;
                case worm.leftVector:
                    this.newWormDirection = direction.DOWN;
                    break;
                case worm.rightVector:
                    this.newWormDirection = direction.UP;
                    break;
                case worm.zeroVector:
                    this.newWormDirection = direction.LEFT;
                    break;
            }
        } else if (mouseCodes.BUTTON_1 === button) {
            switch (directionVector) {
                case worm.upVector:
                    this.newWormDirection = direction.RIGHT;
                    break;
                case worm.downVector:
                    this.newWormDirection = direction.LEFT;
                    break;
                case worm.leftVector:
                    this.newWormDirection = direction.UP;
                    break;
                case worm.rightVector:
                    this.newWormDirection = direction.DOWN;
                    break;
                case worm.zeroVector:
                    this.newWormDirection = direction.RIGHT;
                    break;
            }
        }
    };

    Game.create = function (gameSettings, graphicsDevice, gameSession, leaderboards, badges, keyCodes, mouseCodes) {
        var game = new Game();

        var state = game.state;

        game.currentState = state.PLAY;
        game.gameSession = gameSession;
        game.gameSession.setStatus('Playing');

        game.hasChangedScore = true;
        game.score = 0;
        game.kills = 0;

        game.myWormIndex = -1;

        game.gameStates = [];
        game.gameStates[state.PLAY] = game.play;
        game.gameStates[state.DEAD] = game.dead;
        game.gameStates[state.ERROR] = game.dead;

        game.graphicsDevice = graphicsDevice;
        game.scoreIncrement = 17;

        game.gameSettings = gameSettings;

        if (!gameSettings.width) {
            gameSettings.width = 10;
        }
        if (!gameSettings.height) {
            gameSettings.height = 10;
        }
        if (!gameSettings.boardSpacing) {
            gameSettings.boardSpacing = 1.2;
        }
        if (!gameSettings.maxPlayers) {
            gameSettings.maxPlayers = 1;
        }

        game.leaderboards = leaderboards;
        game.badges = badges;

        game.keyCodes = keyCodes;
        game.mouseCodes = mouseCodes;

        var maxPlayers = gameSettings.maxPlayers;
        var foods = [];
        var worms = [];

        var n;
        for (n = 0; n < maxPlayers; n += 1) {
            foods[n] = {
                x: -1,
                y: -1,
                updated: false,
                eatenBy: null
            };

            worms[n] = Worm.create(gameSettings);
        }

        game.foods = foods;
        game.worms = worms;

        game.newWormDirection = null;

        game.startTime = TurbulenzEngine.time;

        return game;
    };
    return Game;
})();

Game.prototype.state = {
    PLAY: 0,
    DEAD: 1,
    ERROR: 2
};
// Copyright (c) 2011-2012 Turbulenz Limited
;

;

//
// Worm: Worm class
//
var Worm = (function () {
    function Worm() {
    }
    // Changes the worm's direction
    Worm.prototype.changeDirection = function (newDirection) {
        var direction = this.direction;
        var directionVector = this.directionVector;
        var newDirectionVector;

        switch (newDirection) {
            case direction.UP:
                if (directionVector !== this.downVector) {
                    newDirectionVector = this.upVector;
                }
                break;
            case direction.DOWN:
                if (directionVector !== this.upVector) {
                    newDirectionVector = this.downVector;
                }
                break;
            case direction.RIGHT:
                if (directionVector !== this.leftVector) {
                    newDirectionVector = this.rightVector;
                }
                break;
            case direction.LEFT:
                if (directionVector !== this.rightVector) {
                    newDirectionVector = this.leftVector;
                }
                break;
            default:
                newDirectionVector = this.zeroVector;
                break;
        }

        if (newDirectionVector !== undefined) {
            if (directionVector !== newDirectionVector) {
                this.directionVector = newDirectionVector;
                this.updated = true;
            }
        }
    };

    // Update called every frame
    Worm.prototype.update = function () {
        if (this.directionVector !== this.zeroVector) {
            if (this.partsPositionX.length === 1) {
                this.playerInfo.status = "is looking for food.";
            }
            this.moveBody();
            this.moveHead();
            this.updated = true;
        } else {
            this.playerInfo.status = "is thinking about getting some lunch.";
        }

        this.killedBy = null;
    };

    // Collided with something
    Worm.prototype.die = function (killedBy) {
        this.directionVector = this.zeroVector;
        this.killedBy = killedBy;
        this.updated = true;
        this.playerInfo.status = "is bird-food.";
    };

    // Serialize worm information
    Worm.prototype.serialize = function () {
        var directionVector = this.directionVector;
        var direction = this.direction;

        var dir;
        if (directionVector === this.downVector) {
            dir = direction.DOWN;
        } else if (directionVector === this.upVector) {
            dir = direction.UP;
        } else if (directionVector === this.leftVector) {
            dir = direction.LEFT;
        } else if (directionVector === this.rightVector) {
            dir = direction.RIGHT;
        } else {
            dir = direction.NONE;
        }

        var data = {
            dir: dir,
            x: this.partsPositionX.slice(),
            y: this.partsPositionY.slice(),
            score: this.playerInfo.score,
            color: this.playerInfo.color,
            team: this.playerInfo.team,
            status: this.playerInfo.status,
            killedBy: undefined
        };

        var killedBy = this.killedBy;
        if (killedBy !== null) {
            data.killedBy = killedBy;
        }

        return data;
    };

    // Deserialize from external data
    Worm.prototype.deserialize = function (isHost, data) {
        var partsPositionX = this.partsPositionX;
        var partsPositionY = this.partsPositionY;
        var numParts = partsPositionX.length;

        if (!isHost) {
            var killedBy = data.killedBy;
            if (killedBy !== undefined) {
                this.killedBy = killedBy;
            } else {
                this.killedBy = null;
            }
        }

        var direction = this.direction;
        switch (data.dir) {
            case direction.UP:
                this.directionVector = this.upVector;
                break;
            case direction.DOWN:
                this.directionVector = this.downVector;
                break;
            case direction.RIGHT:
                this.directionVector = this.rightVector;
                break;
            case direction.LEFT:
                this.directionVector = this.leftVector;
                break;
            default:
                this.directionVector = this.zeroVector;
                break;
        }

        var newPartsPositionX = data.x;
        var newPartsPositionY = data.y;
        var newNumParts = newPartsPositionX.length;

        if (numParts !== newNumParts) {
            partsPositionX.length = newNumParts;
            partsPositionY.length = newNumParts;
        }

        for (var n = 0; n < newNumParts; n += 1) {
            partsPositionX[n] = newPartsPositionX[n];
            partsPositionY[n] = newPartsPositionY[n];
        }

        this.playerInfo.score = data.score;
        this.playerInfo.team = data.team;
        this.playerInfo.status = data.status;
        this.playerInfo.color = data.color;
    };

    // Moves all of worm parts as required
    Worm.prototype.moveBody = function () {
        var partsPositionX = this.partsPositionX;
        var partsPositionY = this.partsPositionY;
        var length = partsPositionX.length;
        var tailIndex = (length - 1);

        var i;

        // Update the previous tail position
        this.previousTailX = partsPositionX[tailIndex];
        this.previousTailY = partsPositionY[tailIndex];

        for (i = tailIndex; i > 0; i -= 1) {
            partsPositionX[i] = partsPositionX[i - 1];
            partsPositionY[i] = partsPositionY[i - 1];
        }
    };

    // Moves head and loops over board edge if necessary
    Worm.prototype.moveHead = function () {
        var boardWidth = this.boardWidth;
        var boardHeight = this.boardHeight;
        var partsPositionX = this.partsPositionX;
        var partsPositionY = this.partsPositionY;
        var directionVector = this.directionVector;
        var headPositionX = partsPositionX[0];
        var headPositionY = partsPositionY[0];

        // Update head of snake
        headPositionX += directionVector[0];
        headPositionY += directionVector[1];

        this.hasLooped = true;

        if (headPositionX === boardWidth) {
            headPositionX = 0;
        } else if (headPositionX === -1) {
            headPositionX = boardWidth - 1;
        } else if (headPositionY === boardHeight) {
            headPositionY = 0;
        } else if (headPositionY === -1) {
            headPositionY = boardHeight - 1;
        } else {
            this.hasLooped = false;
        }

        partsPositionX[0] = headPositionX;
        partsPositionY[0] = headPositionY;
    };

    // Increases worm length by 1
    Worm.prototype.addToTail = function () {
        var partsPositionX = this.partsPositionX;
        var partsPositionY = this.partsPositionY;
        var length = partsPositionX.length;

        partsPositionX[length] = this.previousTailX;
        partsPositionY[length] = this.previousTailY;

        this.updated = true;

        if (length > 2) {
            this.playerInfo.status = "is still hungry.";
        }

        if (length > 4) {
            this.playerInfo.status = "is getting full.";
        }

        if (length > 8) {
            this.playerInfo.status = "is eating too much.";
        }

        if (length > 10) {
            this.playerInfo.status = "is putting on a bit of weight.";
        }

        if (length > 12) {
            this.playerInfo.status = "is feeling bloated.";
        }
    };

    // Tests for self intersection
    Worm.prototype.isIntersectingSelf = function () {
        var partsPositionX = this.partsPositionX;
        var partsPositionY = this.partsPositionY;
        var length = partsPositionX.length;
        var headX = partsPositionX[0];
        var headY = partsPositionY[0];

        var i;

        for (i = 1; i < length; i += 1) {
            if (partsPositionX[i] === headX && partsPositionY[i] === headY) {
                return true;
            }
        }

        return false;
    };

    // Tests for intersection with other worms
    Worm.prototype.isIntersecting = function (otherWorm) {
        var otherPartsPositionX = otherWorm.partsPositionX;
        var otherPartsPositionY = otherWorm.partsPositionY;
        var otherLength = otherPartsPositionX.length;

        var headX = this.partsPositionX[0];
        var headY = this.partsPositionY[0];

        var i;

        for (i = 0; i < otherLength; i += 1) {
            if (otherPartsPositionX[i] === headX && otherPartsPositionY[i] === headY) {
                return true;
            }
        }

        return false;
    };

    // Tests if position x,y is covered by worm
    Worm.prototype.containsPosition = function (x, y) {
        var partsPositionX = this.partsPositionX;
        var partsPositionY = this.partsPositionY;
        var length = partsPositionX.length;

        var i;

        for (i = 0; i < length; i += 1) {
            if (partsPositionX[i] === x && partsPositionY[i] === y) {
                return true;
            }
        }

        return false;
    };

    // Test if position x,y is covered by worm head
    Worm.prototype.isOnHead = function (x, y) {
        if (this.partsPositionX[0] === x && this.partsPositionY[0] === y) {
            return true;
        }

        return false;
    };

    // Resets worm to original state
    Worm.prototype.reset = function (x, y) {
        this.hasLooped = false;

        var partsPositionX = this.partsPositionX;
        var partsPositionY = this.partsPositionY;

        this.directionVector = this.zeroVector;

        partsPositionX.length = 1;
        partsPositionY.length = 1;

        partsPositionX[0] = x;
        partsPositionY[0] = y;

        this.previousTailX = x;
        this.previousTailY = y;

        this.playerInfo.color = Math.random() > 0.5 ? "green" : "yellow";
        this.playerInfo.score = 0;

        this.updated = true;
    };

    Worm.create = function (gameSettings) {
        var worm = new Worm();

        worm.boardSpacing = gameSettings.boardSpacing;
        worm.boardWidth = gameSettings.width;
        worm.boardHeight = gameSettings.height;
        worm.maxPlayers = gameSettings.maxPlayers;

        worm.directionVector = worm.zeroVector;

        worm.partsPositionX = [];
        worm.partsPositionY = [];
        worm.previousTailX = 0;
        worm.previousTailY = 0;

        worm.killedBy = null;
        worm.updated = false;

        worm.playerInfo = {
            score: 0,
            team: "Worms",
            status: "is thinking about getting some lunch.",
            color: Math.random() > 0.5 ? "green" : "yellow"
        };

        return worm;
    };
    return Worm;
})();

Worm.prototype.direction = {
    NONE: -1,
    UP: 0,
    DOWN: 1,
    RIGHT: 2,
    LEFT: 3
};
Worm.prototype.upVector = [0, 1];
Worm.prototype.downVector = [0, -1];
Worm.prototype.rightVector = [1, 0];
Worm.prototype.leftVector = [-1, 0];
Worm.prototype.zeroVector = [0, 0];
// Copyright (c) 2011-2013 Turbulenz Limited
;

//
// Application: The global for the whole application (singleton)
//
var Application = (function () {
    function Application() {
        this.gameSettings = {
            width: 30,
            height: 16,
            boardSpacing: 1.1,
            maxPlayers: 3
        };
        this.gameTimeStep = 0.05;
        this.networkIds = {
            joining: 0,
            update: 1,
            leaving: 2,
            ping: 3,
            pong: 4
        };
        this.heartbeatTime = 0.5;
        this.staleTime = 1.5;
        this.sceneSetup = false;
    }
    // Error callback - uses window alert
    Application.prototype.errorCallback = function (msg) {
        window.alert(msg);
    };

    // Initialise the application
    Application.prototype.init = function () {
        if (!this.createDevices() || !this.hasShaderSupport()) {
            return;
        }

        var creationFunctions = [
            { func: this.createUserProfile, isDependent: false },
            { func: this.createGameSession, isDependent: false },
            { func: this.createMappingTable, isDependent: true },
            { func: this.createLeaderboardManager, isDependent: true },
            { func: this.createBadgeManager, isDependent: false, noCallback: true },
            { func: this.createMultiplayerSessionManager, isDependent: false, noCallback: true },
            { func: this.createGameLeaderboards, isDependent: true },
            { func: this.createGameBadges, isDependent: false },
            { func: this.createGame, isDependent: true, noCallback: true },
            { func: this.createHTMLWriter, isDependent: true, noCallback: true },
            { func: this.enterLoadingLoop, isDependent: true }
        ];
        this.enterCallbackChain(this, creationFunctions);
    };

    // Update function called in main loop
    Application.prototype.update = function (currentTime) {
        var devices = this.devices;

        devices.inputDevice.update();

        devices.networkDevice.update();

        var gameStep = false, heartbeat = false;

        if ((currentTime - this.previousGameUpdateTime) > this.gameTimeStep) {
            this.previousGameUpdateTime = currentTime;

            gameStep = true;
        }

        if ((currentTime - this.previousHeartbeatTime) > this.heartbeatTime) {
            this.previousHeartbeatTime = currentTime;

            this.checkOthers();

            heartbeat = true;
        }

        if (this.updateGame(gameStep, heartbeat)) {
            this.appScene.update();

            this.leaderboards.update(currentTime);

            this.badges.update(currentTime);

            this.htmlWriter.update();

            return true;
        }

        return false;
    };

    // Update game state
    Application.prototype.updateGame = function (gameStep, heartbeat) {
        var isHost = this.isHost;
        var game = this.game;
        var gameSession = this.gameSession;
        var sortedWorms, worm, playerId, index;
        var ranks = ["1st", "2nd", "3rd"];
        var theOthers = this.others;
        var myID = this.userProfile.username;

        game.update(isHost, gameStep);

        var multiplayerSession = this.multiplayerSession;
        if (multiplayerSession && game.myWormIndex >= 0) {
            var updateData = {
                frame: undefined,
                host: undefined,
                others: undefined
            };

            var sendUpdate = ((TurbulenzEngine.time - this.lastSentMessageTime) > this.heartbeatTime);

            if (this.hasOthers()) {
                var needToSend = game.serializeDelta(isHost, updateData);
                if (needToSend) {
                    sendUpdate = true;
                }
            }

            if (sendUpdate) {
                updateData.frame = this.frameCounter;

                if (isHost) {
                    updateData.host = true;
                }

                var others = {};
                others[multiplayerSession.playerId] = game.myWormIndex;
                updateData.others = others;

                multiplayerSession.sendToAll(this.networkIds.update, JSON.stringify(updateData));

                /*
                if (heartbeat)
                {
                var pingData = {
                ping : Math.floor(TurbulenzEngine.time * 1000)
                };
                multiplayerSession.sendToAll(this.networkIds.ping, JSON.stringify(pingData));
                }
                */
                this.lastSentMessageTime = TurbulenzEngine.time;
            }

            sortedWorms = game.worms.slice(0).sort(function (wormA, wormB) {
                return wormB.playerInfo.score - wormA.playerInfo.score;
            });

            for (index = 0; index < sortedWorms.length; index += 1) {
                sortedWorms[index].playerInfo.rank = ranks[index] || index + 1 + "th";
                sortedWorms[index].playerInfo.sortkey = index;
            }

            myID = multiplayerSession.playerId;
            for (playerId in theOthers) {
                if (theOthers.hasOwnProperty(playerId)) {
                    worm = game.worms[theOthers[playerId].wormIndex];
                    gameSession.setPlayerInfo(playerId, worm.playerInfo);
                }
            }
        }

        if (game.myWormIndex >= 0) {
            worm = game.worms[game.myWormIndex];
            gameSession.setPlayerInfo(myID, worm.playerInfo);
        }

        if (gameStep) {
            this.frameCounter += 1;
            this.needToRender = true;
        }

        return this.needToRender;
    };

    // Render function called in main loop
    Application.prototype.render = function (currentTime) {
        var appScene = this.appScene;
        var renderer = appScene.renderer;
        var graphicsDevice = this.devices.graphicsDevice;

        renderer.update(graphicsDevice, appScene.camera, appScene.scene, currentTime);

        if (graphicsDevice.beginFrame()) {
            renderer.draw(graphicsDevice, appScene.clearColor);

            this.drawUI();

            graphicsDevice.endFrame();
        }

        this.needToRender = false;
    };

    // Load UI
    Application.prototype.loadUI = function () {
        var managers = this.managers;
        managers.fontManager.load('fonts/hero.fnt');
        managers.shaderManager.load('shaders/font.cgfx');
    };

    Application.prototype.hasUILoaded = function () {
        var managers = this.managers;
        var fontManager = managers.fontManager;
        var shaderManager = managers.shaderManager;
        if (fontManager.getNumPendingFonts() === 0 && shaderManager.getNumPendingShaders() === 0) {
            if (!this.technique2D) {
                var devices = this.devices;

                this.font = fontManager.get('fonts/hero.fnt');

                var shader = shaderManager.get('shaders/font.cgfx');
                this.technique2D = shader.getTechnique('font');
                this.technique2Dparameters = devices.graphicsDevice.createTechniqueParameters({
                    clipSpace: devices.mathDevice.v4BuildZero(),
                    alphaRef: 0.01,
                    color: devices.mathDevice.v4BuildOne()
                });
            }

            return true;
        }

        return false;
    };

    // Draw UI
    Application.prototype.drawUI = function () {
        var msg = '';
        var words = [];
        var word;
        var linecount;

        var game = this.game;
        var devices = this.devices;
        var graphicsDevice = devices.graphicsDevice;
        var mathDevice = devices.mathDevice;

        var width = graphicsDevice.width;
        var height = graphicsDevice.height;

        var font = this.font;
        var technique2Dparameters = this.technique2Dparameters;

        graphicsDevice.setTechnique(this.technique2D);

        technique2Dparameters.clipSpace = mathDevice.v4Build(2.0 / width, -2.0 / height, -1.0, 1.0, technique2Dparameters.clipSpace);
        graphicsDevice.setTechniqueParameters(technique2Dparameters);

        // Draw score
        font.drawTextRect('Score: ' + game.score, {
            rect: [10, 10, (width * 0.5) - 10, 32],
            scale: 1.0,
            spacing: 0,
            alignment: 0
        });

        font.drawTextRect('Kills: ' + game.kills, {
            rect: [(width * 0.5), 10, (width * 0.5) - 10, 32],
            scale: 1.0,
            spacing: 0,
            alignment: 2
        });

        if (game.currentState === game.state.ERROR) {
            font.drawTextRect('Failed to join game', {
                rect: [0, 40, (width - 10), 32],
                scale: 1.5,
                spacing: 0,
                alignment: 1
            });
            words = game.join_error.split(' ');
            linecount = 0;
            while (words.length > 0) {
                msg = '';
                while (msg.length < 40 && words.length > 0) {
                    word = words.shift();
                    msg += word + ' ';

                    if (word.indexOf('\n') !== -1) {
                        break;
                    }
                }
                font.drawTextRect(msg, {
                    rect: [0, 104 + (linecount * 33), (width - 10), 32],
                    scale: 1.0,
                    spacing: 0,
                    alignment: 1
                });
                linecount += 1;
            }
            font.drawTextRect('Press SPACE to start new game', {
                rect: [0, 104 + (linecount * 33) + 20, (width - 10), 32],
                scale: 1.0,
                spacing: 0,
                alignment: 1
            });
        } else if (game.currentState === game.state.DEAD) {
            font.drawTextRect('DEAD', {
                rect: [0, 20, (width - 10), 32],
                scale: 1.5,
                spacing: 0,
                alignment: 1
            });
            font.drawTextRect('Press SPACE to continue', {
                rect: [0, 84, (width - 10), 32],
                scale: 1.0,
                spacing: 0,
                alignment: 1
            });
        }

        if (!this.multiplayerSession) {
            if (game.currentState !== game.state.ERROR) {
                font.drawTextRect('No multiplayer servers. Playing solo!', {
                    rect: [0, (height - 32), (width - 10), 32],
                    scale: 0.5,
                    spacing: 0,
                    alignment: 1
                });
            }
        } else {
            if (this.isHost) {
                graphicsDevice.setScissor((width - 6), 2, 4, 4);
                graphicsDevice.clear([1, 0, 0, 1]);
                graphicsDevice.setScissor(0, 0, width, height);
            }

            if (!this.multiplayerSession.connected()) {
                font.drawTextRect('Connection lost!', {
                    rect: [0, (height - 32), (width - 10), 32],
                    scale: 0.5,
                    spacing: 0,
                    alignment: 1
                });
            }
        }
    };

    // Checks for shading language support
    Application.prototype.hasShaderSupport = function () {
        var graphicsDevice = this.devices.graphicsDevice;

        if (!graphicsDevice.shadingLanguageVersion) {
            this.errorCallback("No shading language support detected.\nPlease check your graphics drivers are up to date.");
            graphicsDevice = null;
            return false;
        }
        return true;
    };

    // Create the device interfaces required
    Application.prototype.createDevices = function () {
        var devices = this.devices;
        var managers = this.managers;
        var errorCallback = this.errorCallback;

        var graphicsDeviceParameters = { multisample: 16 };
        var graphicsDevice = TurbulenzEngine.createGraphicsDevice(graphicsDeviceParameters);

        var mathDeviceParameters = {};
        var mathDevice = TurbulenzEngine.createMathDevice(mathDeviceParameters);

        var inputDeviceParameters = {};
        var inputDevice = TurbulenzEngine.createInputDevice(inputDeviceParameters);

        var networkDeviceParameters = {};
        var networkDevice = TurbulenzEngine.createNetworkDevice(networkDeviceParameters);

        devices.graphicsDevice = graphicsDevice;
        devices.mathDevice = mathDevice;
        devices.inputDevice = inputDevice;
        devices.networkDevice = networkDevice;

        var requestHandlerParameters = {};
        var requestHandler = RequestHandler.create(requestHandlerParameters);
        this.requestHandler = requestHandler;

        managers.textureManager = TextureManager.create(graphicsDevice, requestHandler, null, errorCallback);
        managers.shaderManager = ShaderManager.create(graphicsDevice, requestHandler, null, errorCallback);
        managers.effectManager = EffectManager.create();
        managers.fontManager = FontManager.create(graphicsDevice, requestHandler, null, errorCallback);

        return true;
    };

    // Calls functions in order
    Application.prototype.enterCallbackChain = function (context, functions) {
        var length = functions.length;
        var localCallback;
        var callNextFunction;

        // Invariant: currentFunction always refers to the last uncalled function
        var currentFunction = 0;

        // Invariant: activeCallbacks refers to the number of functions whose callbacks have not yet been received
        var activeCallbacks = 0;

        callNextFunction = function callNextFunctionFn() {
            if (!functions[currentFunction].noCallback) {
                activeCallbacks += 1;
            }

            functions[currentFunction].func.call(context, localCallback, arguments);

            currentFunction += 1;
        };

        localCallback = function localCallbackFn() {
            activeCallbacks -= 1;

            if (activeCallbacks === 0 && currentFunction < length) {
                // No active callbacks so immediately call next function
                callNextFunction();

                while (currentFunction < length && ((0 === activeCallbacks) || (!functions[currentFunction].isDependent))) {
                    callNextFunction();
                }
            }
        };

        // Start the async callback chain
        callNextFunction();
    };

    // Creates the game with the settings provided
    Application.prototype.createGame = function () {
        var devices = this.devices;
        var inputDevice = devices.inputDevice;

        this.game = Game.create(this.gameSettings, devices.graphicsDevice, this.gameSession, this.leaderboards, this.badges, inputDevice.keyCodes, inputDevice.mouseCodes);

        this.createInputDeviceCallbacks();
    };

    // Adds onKeyDown functions to inputDevice
    Application.prototype.createInputDeviceCallbacks = function () {
        var game = this.game;
        var inputDevice = this.devices.inputDevice;

        // Closure for keyDown callback
        function onKeyDown(keynum) {
            game.onKeyDown(keynum);
        }

        function onMouseDown(keynum) {
            game.onMouseDown(keynum);
        }

        inputDevice.addEventListener('keydown', onKeyDown);
        inputDevice.addEventListener('mousedown', onMouseDown);
    };

    // Create GameLeaderboards
    Application.prototype.createGameLeaderboards = function (callback) {
        this.leaderboards = GameLeaderboards.create(this.leaderboardManager, callback);
    };

    // Create GameBadges
    Application.prototype.createGameBadges = function (callback) {
        this.badges = GameBadges.create(this.badgeManager, callback);
    };

    // Create HTML Writer
    Application.prototype.createHTMLWriter = function () {
        // Must be created after badges, leaderboards, and game have been initialised
        this.htmlWriter = HtmlWriter.create(this.leaderboards, this.badges, this.game);
    };

    // Create multiplayer session
    Application.prototype.startMultiplayerSession = function () {
        var that = this;
        var queue = this.multiplayerSessionManager.getJoinRequestQueue();
        var pendingJoinRequest = queue.shift();

        function localConnectingStateLoop() {
            return that.connectingStateLoop();
        }

        function localMainStateLoop() {
            return that.mainStateLoop();
        }

        function onMultiplayerMessage(senderID, messageType, messageData) {
            that.onMessage(senderID, messageType, messageData);
        }

        function onMultiplayerClose() {
            that.errorCallback("Connection lost!");
        }

        // Called when a new multiplayer session is joined
        function startNewGame() {
            var game = that.game;
            var myPrevWormIndex = game.myWormIndex;
            game.myWormIndex = -1;
            if (that.sceneSetup && myPrevWormIndex >= 0) {
                that.appScene.resetWorm(myPrevWormIndex);
            }
            game.currentState = game.state.PLAY;
            delete game.join_error;
            that.gameSession.clearAllPlayerInfo();
        }

        function multiplayerSessionCreateError() {
            startNewGame();

            if (that.multiplayerSession) {
                that.multiplayerSession.destroy();
                that.multiplayerSession = null;
            }
            that.isHost = true;
            that.game.badges = that.badges;
            that.game.leaderboards = that.leaderboards;
            that.game.start();

            queue.clear();
            queue.resume();
        }

        function multiplayerSessionSuccess(multiplayerSession, numplayers) {
            if (1 === numplayers) {
                that.isHost = true;
                that.game.start();
            }

            that.multiplayerSession = multiplayerSession;

            multiplayerSession.onmessage = onMultiplayerMessage;

            multiplayerSession.onclose = onMultiplayerClose;

            multiplayerSession.sendToAll(that.networkIds.joining);

            that.connectionTime = TurbulenzEngine.time;

            queue.clear();
            queue.resume();
        }

        function multiplayerSessionJoinError(errMsg, status) {
            // Set up an error to display
            that.game.currentState = that.game.state.ERROR;
            that.game.join_error = 'I\'m sorry, something has gone wrong. Please try again. \n (Technical stuff: ' + errMsg + ')';
            if (status === 404) {
                that.game.join_error = 'I\'m sorry, that game has ended.';
            }
            if (status === 409 && errMsg.match('private')) {
                that.game.join_error = 'I\'m sorry, you can\'t join that game right now. You can only join your ' + 'friend\'s games or public games.';
            }
            if (status === 409 && errMsg.match('slot')) {
                that.game.join_error = 'I\'m sorry, you can\'t join that game right now as it is full.' + ' Try again later!';
            }

            if (!that.sceneSetup) {
                that.appScene.setupScene();
                that.sceneSetup = true;
            }

            TurbulenzEngine.clearInterval(that.intervalID);
            that.intervalID = TurbulenzEngine.setInterval(localMainStateLoop, (1000 / 10));

            // Allow user to try to join another game if required
            queue.clear();
            queue.resume();

            that.game.join_error_cb = function () {
                delete that.game.join_error_cb;
                queue.pause();
                TurbulenzEngine.clearInterval(that.intervalID);
                that.intervalID = TurbulenzEngine.setInterval(localConnectingStateLoop, (1000 / 10));
                that.game.currentState = that.game.state.PLAY;
                that.multiplayerSessionManager.joinOrCreateSession(that.gameSettings.maxPlayers, multiplayerSessionSuccess, multiplayerSessionCreateError);
            };
        }

        function onJoinEvent(multiplayerSessionId) {
            var oldMultiplayerSession = that.multiplayerSession;
            if (!oldMultiplayerSession || oldMultiplayerSession.sessionId !== multiplayerSessionId) {
                if (oldMultiplayerSession) {
                    oldMultiplayerSession.sendToAll(that.networkIds.leaving);
                    oldMultiplayerSession.destroy();
                    that.multiplayerSession = null;
                    that.others = {};
                }

                TurbulenzEngine.clearInterval(that.intervalID);
                that.intervalID = TurbulenzEngine.setInterval(localConnectingStateLoop, (1000 / 10));

                startNewGame();

                queue.pause();

                that.multiplayerSessionManager.joinSession(multiplayerSessionId, multiplayerSessionSuccess, multiplayerSessionJoinError);
            }
        }

        queue.onEvent(onJoinEvent, this);

        if (pendingJoinRequest) {
            this.multiplayerSessionManager.joinSession(pendingJoinRequest, multiplayerSessionSuccess, multiplayerSessionJoinError);
        } else {
            this.multiplayerSessionManager.joinOrCreateSession(this.gameSettings.maxPlayers, multiplayerSessionSuccess, multiplayerSessionCreateError);
        }
    };

    // Create game session
    Application.prototype.createGameSession = function (callback) {
        this.gameSession = TurbulenzServices.createGameSession(this.requestHandler, callback);

        // Setup static teamlist for ordering teams
        this.gameSession.setTeamInfo(['Worms', 'Snakes']);
    };

    // Create a user profile
    Application.prototype.createUserProfile = function (callback) {
        this.userProfile = TurbulenzServices.createUserProfile(this.requestHandler, callback);
    };

    // Create mapping table
    Application.prototype.createMappingTable = function (callback) {
        this.mappingTable = TurbulenzServices.createMappingTable(this.requestHandler, this.gameSession, callback);
    };

    // Create leaderboard manager
    Application.prototype.createLeaderboardManager = function (callback) {
        var that = this;

        function createLeaderboardManagerError() {
            that.leaderboardManager = null;
            callback();
        }

        this.leaderboardManager = TurbulenzServices.createLeaderboardManager(this.requestHandler, this.gameSession, callback, createLeaderboardManagerError);
    };

    // Create badge manager
    Application.prototype.createBadgeManager = function () {
        if (this.leaderboardManager) {
            this.badgeManager = TurbulenzServices.createBadgeManager(this.requestHandler, this.gameSession);
        }
    };

    Application.prototype.createMultiplayerSessionManager = function () {
        this.multiplayerSessionManager = TurbulenzServices.createMultiplayerSessionManager(this.requestHandler, this.gameSession);
    };

    // Starts loading scene and creates an interval to check loading progress
    Application.prototype.enterLoadingLoop = function () {
        var that = this;
        var managers = this.managers;
        var mappingTable = this.mappingTable;
        var urlMapping = mappingTable.urlMapping;
        var assetPrefix = mappingTable.assetPrefix;

        managers.textureManager.setPathRemapping(urlMapping, assetPrefix);
        managers.shaderManager.setPathRemapping(urlMapping, assetPrefix);
        managers.fontManager.setPathRemapping(urlMapping, assetPrefix);

        this.appScene = AppScene.create(this.devices, this.managers, this.requestHandler, this.mappingTable, this.game);
        this.loadUI();

        // Enter loading state
        function localLoadingStateLoop() {
            return that.loadingStateLoop();
        }
        this.intervalID = TurbulenzEngine.setInterval(localLoadingStateLoop, (1000 / 10));
    };

    // Called until assets have been loaded at which point the connecting loop is entered
    Application.prototype.loadingStateLoop = function () {
        var that = this;

        function localConnectingStateLoop() {
            return that.connectingStateLoop();
        }

        if (this.appScene.hasLoaded() && this.hasUILoaded()) {
            TurbulenzEngine.clearInterval(this.intervalID);

            this.startMultiplayerSession();

            this.intervalID = TurbulenzEngine.setInterval(localConnectingStateLoop, (1000 / 10));
        }
    };

    // Called until connected to the multiplayer session at which point the main loop is entered
    Application.prototype.connectingStateLoop = function () {
        var that = this;

        function localMainStateLoop() {
            return that.mainStateLoop();
        }

        this.devices.networkDevice.update();

        if (this.game.myWormIndex >= 0) {
            TurbulenzEngine.clearInterval(this.intervalID);

            if (!this.sceneSetup) {
                this.appScene.setupScene();
                this.sceneSetup = true;
            }

            this.intervalID = TurbulenzEngine.setInterval(localMainStateLoop, (1000 / 60));
        } else {
            if (this.multiplayerSession) {
                var currentTime = TurbulenzEngine.time;
                var connectionTime = this.connectionTime;
                var staleTime = this.staleTime;
                if ((connectionTime + staleTime) < currentTime) {
                    this.isHost = true;
                    this.game.start();
                } else if ((connectionTime + (staleTime * 0.5)) < currentTime) {
                    // Keep requesting to join to avoid problems when starting in the middle of a host transition
                    this.multiplayerSession.sendToAll(this.networkIds.joining);
                }
            }
        }
    };

    Application.prototype.mainStateLoop = function () {
        var currentTime = TurbulenzEngine.time;
        if (this.update(currentTime)) {
            this.render(currentTime);
        }
    };

    Application.prototype.onMessage = function (senderID, messageType, messageData) {
        //Utilities.log(senderID, messageType, messageData);
        var networkIds = this.networkIds;

        switch (messageType) {
            case networkIds.joining:
                this.onJoiningMessage(senderID);
                break;

            case networkIds.update:
                this.onUpdateMessage(senderID, messageData);
                break;

            case networkIds.leaving:
                this.onLeavingMessage(senderID);
                break;

            case networkIds.ping:
                this.onPingMessage(senderID, messageData);
                break;

            case networkIds.pong:
                this.onPongMessage(senderID, messageData);
                break;
        }
    };

    Application.prototype.onJoiningMessage = function (senderID) {
        var multiplayerSession = this.multiplayerSession;
        var myID = multiplayerSession.playerId;
        var networkIds = this.networkIds;
        var theOthers = this.others;
        var other = theOthers[senderID];
        var game = this.game;
        var updateData, others, n, otherID;
        var wormIndex = game.myWormIndex;

        var time = TurbulenzEngine.time;

        if (other === undefined) {
            if (!this.isHost) {
                return;
            }

            var maxPlayers = this.gameSettings.maxPlayers;

            var usedWormIndex = {};
            usedWormIndex[wormIndex] = true;

            var existingPlayers = [];

            others = {};
            others[myID] = wormIndex;
            for (otherID in theOthers) {
                if (theOthers.hasOwnProperty(otherID)) {
                    var another = theOthers[otherID];
                    wormIndex = another.wormIndex;
                    usedWormIndex[wormIndex] = true;
                    others[otherID] = wormIndex;

                    if (otherID !== senderID) {
                        existingPlayers.push(otherID);
                    }
                }
            }

            for (n = 0; n < maxPlayers; n += 1) {
                if (!usedWormIndex[n]) {
                    theOthers[senderID] = {
                        wormIndex: n,
                        heartbeat: time
                    };

                    others[senderID] = n;

                    break;
                }
            }

            updateData = {
                frame: this.frameCounter,
                others: others,
                host: true
            };

            if (0 < existingPlayers.length) {
                multiplayerSession.sendToGroup(existingPlayers, networkIds.update, JSON.stringify(updateData));
            }

            // Add current game state
            game.serialize(updateData);

            // Send new player current game state
            multiplayerSession.sendTo(senderID, networkIds.update, JSON.stringify(updateData));

            this.lastSentMessageTime = time;
        } else {
            // Resend wormindex information, probably just a temporary disconnection
            others = {};
            others[myID] = wormIndex;
            for (otherID in theOthers) {
                if (theOthers.hasOwnProperty(otherID)) {
                    others[otherID] = theOthers[otherID].wormIndex;
                }
            }

            updateData = {
                frame: this.frameCounter,
                others: others,
                host: true
            };

            // Send new player current game state
            multiplayerSession.sendTo(senderID, networkIds.update, JSON.stringify(updateData));
        }
    };

    Application.prototype.onUpdateMessage = function (senderID, messageData) {
        var multiplayerSession = this.multiplayerSession;
        var myID = multiplayerSession.playerId;
        var networkIds = this.networkIds;
        var theOthers = this.others;
        var other = theOthers[senderID];
        var game = this.game;
        var updateData, others, otherID, wormIndex;

        var time = TurbulenzEngine.time;

        if (other !== undefined) {
            other.heartbeat = time;
        }

        if (messageData) {
            updateData = JSON.parse(messageData);
            if (updateData) {
                others = updateData.others;
                if (others !== undefined) {
                    for (otherID in others) {
                        if (others.hasOwnProperty(otherID)) {
                            wormIndex = others[otherID];

                            if (otherID === myID) {
                                if (game.myWormIndex < 0) {
                                    this.frameCounter = updateData.frame;
                                    if (this.sceneSetup) {
                                        this.appScene.resetWorm(wormIndex);
                                    }
                                }
                                game.myWormIndex = wormIndex;
                                //Utilities.log(myID + ' got worm index ' + wormIndex);
                            } else {
                                if (wormIndex === game.myWormIndex) {
                                    if (myID > otherID) {
                                        //Utilities.log('Resetting worm index');
                                        game.myWormIndex = -1;
                                        multiplayerSession.sendTo(senderID, networkIds.leaving);
                                        multiplayerSession.sendTo(senderID, networkIds.joining);
                                        return;
                                    }
                                }

                                var another = theOthers[otherID];
                                if (another === undefined) {
                                    theOthers[otherID] = another = {};
                                }
                                another.wormIndex = wormIndex;
                                another.heartbeat = time;
                            }
                        }
                    }

                    if (other === undefined) {
                        other = theOthers[senderID];
                    }
                }

                if (updateData.host) {
                    if (this.isHost) {
                        if ((other !== undefined && other.wormIndex < game.myWormIndex) || (other === undefined && senderID < myID)) {
                            //Utilities.log(myID + ', ' + senderID + ' host index conflict');
                            // This instance should not be the host
                            this.isHost = false;
                            game.myWormIndex = -1;
                            multiplayerSession.sendTo(senderID, networkIds.leaving);
                            multiplayerSession.sendTo(senderID, networkIds.joining);
                        }
                        return;
                    }

                    if (other !== undefined) {
                        other.host = true;
                    }

                    this.hostFrameCounter = updateData.frame;
                }

                if (game.deserialize(this.isHost, updateData)) {
                    this.needToRender = true;
                }
            }
        }
    };

    Application.prototype.onLeavingMessage = function (senderID) {
        var other = this.others[senderID];
        this.gameSession.removePlayerInfo(senderID);
        if (other !== undefined) {
            other.heartbeat = 0;

            this.previousHeartbeatTime = 0;
        }
    };

    Application.prototype.onPingMessage = function (senderID, messageData) {
        var time = TurbulenzEngine.time;

        if (messageData) {
            var pingData = JSON.parse(messageData);
            if (pingData) {
                pingData.pong = Math.floor(time * 1000);

                this.multiplayerSession.sendTo(senderID, this.networkIds.pong, JSON.stringify(pingData));
            }
        }
    };

    Application.prototype.onPongMessage = function (senderID, messageData) {
        var time = TurbulenzEngine.time;

        var other = this.others[senderID];
        if (other !== undefined) {
            other.heartbeat = time;

            var pongData = JSON.parse(messageData);
            if (pongData) {
                // Update latency by calculating the median of the history
                var latency = (time - (pongData.ping * 0.001));
                var latencyHistory = other.latencyHistory;
                var nextLatencyHistory = other.nextLatencyHistory;
                if (latencyHistory === undefined) {
                    other.latencyHistory = latencyHistory = [];
                    other.nextLatencyHistory = nextLatencyHistory = 0;
                }

                /*jshint bitwise: false*/
                var numLatencies = latencyHistory.length;
                if (32 <= numLatencies) {
                    other.nextLatencyHistory = ((nextLatencyHistory + 1) & 31);
                    latencyHistory[nextLatencyHistory] = latency;
                } else {
                    latencyHistory[numLatencies] = latency;
                    numLatencies += 1;
                }
                if (1 < numLatencies) {
                    // sort history numerically lower to higher
                    latencyHistory = latencyHistory.slice();
                    latencyHistory.sort(function (a, b) {
                        return (a - b);
                    });

                    var medianIndex = (numLatencies >> 1);
                    if (numLatencies & 1) {
                        latency = latencyHistory[medianIndex];
                    } else {
                        latency = ((latencyHistory[medianIndex - 1] + latencyHistory[medianIndex]) * 0.5);
                    }
                }

                /*jshint bitwise: true*/
                other.latency = latency;
                other.time = ((pongData.pong * 0.001) + (0.5 * latency));
            }
        }
    };

    // Migrate host to player with lower index
    Application.prototype.migrateHost = function () {
        var myWormIndex = this.game.myWormIndex;

        var others = this.others;
        for (var otherID in others) {
            if (others.hasOwnProperty(otherID)) {
                var wormIndex = others[otherID].wormIndex;
                if (wormIndex < myWormIndex) {
                    return;
                }
            }
        }

        // If we reach this code we should be the host
        this.isHost = true;
        //Utilities.log("You have become the host of the game!");
    };

    // Check state of others
    Application.prototype.checkOthers = function () {
        var staleTime = (TurbulenzEngine.time - this.staleTime);
        var others = this.others;
        var needToMigrate = (!this.isHost);
        var staleWorms = [];
        var numStale = 0;
        var other;

        for (var otherID in others) {
            if (others.hasOwnProperty(otherID)) {
                other = others[otherID];
                if (other.heartbeat < staleTime) {
                    staleWorms[numStale] = other.wormIndex;
                    numStale += 1;
                    this.gameSession.removePlayerInfo(otherID);
                    delete others[otherID];
                } else if (other.host) {
                    needToMigrate = false;
                }
            }
        }

        if (needToMigrate) {
            this.migrateHost();
        }

        if (0 < numStale) {
            if (this.isHost) {
                var game = this.game;
                var n = 0;
                do {
                    game.placeWorm(staleWorms[n]);
                    n += 1;
                } while(n < numStale);
            }
        }
    };

    // is anyone listening?
    Application.prototype.hasOthers = function () {
        var others = this.others;
        var otherID;
        for (otherID in others) {
            if (others.hasOwnProperty(otherID)) {
                return true;
            }
        }
        return false;
    };

    // Attempts to free memory - called from onbeforeunload and/or TurbulenzEngine.onUnload
    Application.prototype.shutdown = function () {
        if (!this.hasShutdown) {
            this.hasShutdown = true;

            TurbulenzEngine.clearInterval(this.intervalID);

            // Leave the multiplayer session
            var multiplayerSession = this.multiplayerSession;
            if (multiplayerSession) {
                multiplayerSession.sendToAll(this.networkIds.leaving);
            }

            // Destroy any remaining multiplayer sessions
            this.multiplayerSessionManager.destroy();

            // Tell the Turbulenz Services that the game session is over
            this.gameSession.clearAllPlayerInfo();
            this.gameSession.destroy();

            // Destroy vars in reverse order from creation
            this.technique2Dparameters = null;
            this.technique2D = null;
            this.font = null;
            this.previousHeartbeatTime = null;
            this.others = null;
            this.managers = null;
            this.devices = null;
            this.game = null;
            this.appScene = null;
            this.htmlWriter = null;
            this.multiplayerSession = null;
            this.badges = null;
            this.leaderboards = null;
            this.badgeManager = null;
            this.multiplayerSessionManager = null;
            this.leaderboardManager = null;
            this.gameSession = null;
            this.userProfile = null;
            this.previousGameUpdateTime = null;
            this.runInEngine = null;

            // Attempt to force clearing of the garbage collector
            TurbulenzEngine.flush();

            // Clear native engine references
            this.devices = null;
        }
    };

    Application.create = // Application constructor function
    function (runInEngine) {
        var application = new Application();

        // Ensures shutdown function is only called once
        application.hasShutDown = false;
        application.runInEngine = runInEngine;

        application.previousGameUpdateTime = 0;
        application.gameSession = null;
        application.userProfile = null;
        application.multiplayerSession = null;
        application.leaderboardManager = null;
        application.badgeManager = null;
        application.multiplayerSessionManager = null;
        application.leaderboards = null;
        application.badges = null;
        application.htmlWriter = null;
        application.appScene = null;
        application.game = null;
        application.devices = {};
        application.managers = {};
        application.others = {};
        application.isHost = false;
        application.connectionTime = 0;
        application.previousHeartbeatTime = 0;
        application.lastSentMessageTime = 0;
        application.frameCounter = 0;
        application.hostFrameCounter = 0;
        application.needToRender = true;

        // UI
        application.font = null;
        application.technique2D = null;
        application.technique2Dparameters = null;

        return application;
    };
    return Application;
})();
