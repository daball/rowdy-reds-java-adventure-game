declare class AppScene {
    public renderer: SimpleRendering;
    public materials: {
        [name: string]: any;
    };
    public devices: {
        mathDevice: MathDevice;
        graphicsDevice: GraphicsDevice;
    };
    public requestHandler: RequestHandler;
    public managers: {
        shaderManager: ShaderManager;
        textureManager: TextureManager;
        effectManager: EffectManager;
    };
    public mappingTable: any;
    public game: Game;
    public previousGameState: number;
    public gameNode: SceneNode;
    public boardNode: SceneNode;
    public wormNodes: SceneNode[];
    public boardCubeNode: SceneNode;
    public wormCubeNode: SceneNode;
    public foodCubeNodes: SceneNode[];
    public camera: Camera;
    public scene: Scene;
    public sceneLoader: SceneLoader;
    public clearColor: any;
    public playerColor: any;
    public currentPlayerColor: string;
    public wormPartsNodes: SceneNode[][];
    public wormCubeRenderable: Renderable;
    public otherWormCubeRenderable: Renderable;
    public errorCallback(msg): void;
    public hasLoaded(): boolean;
    public loadCube(): void;
    public createMaterials(): void;
    public setupScene(): void;
    public setupCamera(): void;
    public resetWorm(wormIndex): void;
    public createCubeNodes(maxPlayers): void;
    public createNodeStructure(maxPlayers): void;
    public createBoard(boardCenter, boardSpacing, horizontalCubes, verticalCubes): void;
    public update(): void;
    public updateCamera(): void;
    public updateGameScene(): void;
    public moveWormNodes(wormIndex): void;
    public updateTransforms(): void;
    public setMaterial(renderable, materialName): void;
    static create(devices, managers, requestHandler, mappingTable, game): AppScene;
}
declare class GameBadges {
    public badgesManager: BadgeManager;
    public onInitialization: () => void;
    public unachievedBadges: {
        [name: string]: Badge;
    };
    public achievedBadges: {
        [name: string]: Badge;
    };
    public awardedBadges: {
        [name: string]: boolean;
    };
    public hasChanged: boolean;
    public hasChangedData: boolean;
    public isActive: boolean;
    public hasConnection: boolean;
    public updateInterval: number;
    public maxInterval: number;
    public lastUpdateTime: number;
    public init(): void;
    public initialiseBadgesErrorCallback(msg, status): void;
    public initialiseBadges(badges): void;
    public initialiseUserBadges(badges): void;
    public addBadge(badgeName): void;
    public awardBadge(badgeName): void;
    public refresh(): void;
    public updateUserBadgeProgressCallback(badge): void;
    public updateUserBadgeErrorCallback(msg, status, badgeData): void;
    public awardUserBadgeCallback(badge): void;
    public awardUserBadgeErrorCallback(msg, status, badgeData): void;
    public update(currentTime): void;
    static create(badgesManager, onInitialization): GameBadges;
}
declare class Badge {
    public isNonProgress: boolean;
    public currentProgress: number;
    public totalRequired: number;
    public hasProgressed: boolean;
    public predescription: string;
    public description: string;
    public title: string;
    public isUpdating: boolean;
    public onBeforeSet(): void;
    public onSuccessfulSet(currentProgress): void;
    public onUnsuccessfulSet(): void;
    public addProgress(): void;
    public award(): void;
    public isAchieved(): boolean;
    static create(totalRequired: number, predescription: string, description: string, title: string): Badge;
}
declare class GameLeaderboards {
    public leaderboardManager: LeaderboardManager;
    public onInitialization: () => void;
    public leaderboards: {
        [name: string]: Leaderboard;
    };
    public hasChanged: boolean;
    public hasChangedData: boolean;
    public isActive: boolean;
    public hasConnection: boolean;
    public updateInterval: number;
    public maxInterval: number;
    public lastUpdateTime: number;
    public init(): void;
    public initialiseLeaderboardsErrorCallback(msg, status): void;
    public initialiseLeaderboards(leaderboardArray): void;
    public setScore(leaderboardName, score): void;
    public refresh(): void;
    public refreshLeaderboard(leaderboardName, score): void;
    public update(currentTime): void;
    public setLeaderboardCallback(key, score, newBest, bestScore): void;
    public setErrorCallback(msg, status, setFunction, leaderboardData): void;
    static create(leaderboardManager, onInitialization): GameLeaderboards;
}
declare class HtmlWriter {
    public leaderboards: GameLeaderboards;
    public badges: GameBadges;
    public game: Game;
    public leaderboardsDiv: HTMLElement;
    public badgesDiv: HTMLElement;
    public scoreDiv: HTMLElement;
    public killsDiv: HTMLElement;
    public leaderboardDivs: {
        [name: string]: HTMLElement;
    };
    public badgeDivs: {
        [name: string]: HTMLElement;
    };
    public runAsTzjs: boolean;
    public init(scoreDivID, killsDivID, leaderboardDivId, badgesDivId): void;
    public addLeaderboardHtml(leaderboard): void;
    public addBadgeHtml(badge): void;
    public writeScore(): void;
    public writeLeaderboards(): void;
    public writeBadges(): void;
    public writeLeaderboard(leaderboardName): void;
    public writeAchievedBadge(badgeName): void;
    public writeUnachievedBadge(badgeName): void;
    public writeTextContent(element, value): void;
    public update(): void;
    static create(leaderboards, badges, game): HtmlWriter;
}
declare class Leaderboard {
    public sortBy: number;
    public title: string;
    public isUpdating: boolean;
    public newScore: number;
    public currentScore: number;
    public setScore: (score: number) => void;
    public hasImprovedScore: () => boolean;
    public onBeforeSet(): void;
    public onSuccessfulSet(bestScore): void;
    public onUnsuccessfulSet(): void;
    static create(sortBy, title): Leaderboard;
}
/**
* The DynamicUI manager sends events to the DynamicUI server to create instances of UI elements on the host website. It
* then manages updates to the UI either responding to requests for the value for a specific UI element, or pushing
* values to elements referenced by id.
*/
declare class DynamicUIManager {
    public _objects: any;
    public _setters: any;
    public _getters: any;
    public _watchGroup: number;
    /**
    * Generates a new id for use in the dynamicUI system
    *
    * @return A new unique id to use
    */
    public _newId: () => number;
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
    public _addUI(type, title, getValue, setValue, groupId, options): number;
    /**
    * Utility function to handle "watch stashed object" events.
    *
    * @param paramstring The JSONified request
    */
    public _watchStashedObject(paramstring): void;
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
    public addSlider(title, getValue, setValue, groupId, options): number;
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
    public addCheckbox(title, getValue, setValue, groupId, options): number;
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
    public addSelect(title, getValue, setValue, groupId, options): number;
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
    public addWatch(title, getValue, setValue, groupId, options): number;
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
    public addRadioButton(title, getValue, setValue, groupId, options): number;
    /**
    * Destroys the specified UI element.
    *
    * @param id The Id of the element to destroy. If the element is a group, the group and all its children are
    * destroyed
    */
    public destroy(id): void;
    /**
    * Updates the specified UI element with a new value.
    *
    * @param id The Id of the element to update
    * @param value The value to send to the UI
    */
    public pushValue(id, value): void;
    /**
    * Adds a group to the dynamid UI.
    *
    * @param {String} title The title of the group
    * @param groupId The parent group to add this new group to
    * @returns The id of the newly created group.
    */
    public addGroup(title, groupId?): number;
    /**
    * Adds a UI element to an existing group. The element is moved, so if it is already a member of a group it
    * will be removed from that group and added to the group specified in the function call.
    *
    * @param id The id of the element to move
    * @param groupId The parent group to add this new group to
    */
    public addToGroup(id, groupId): void;
    /**
    * Removes a UI element from a group. This does not destroy the UI element so it can be used to temporarily hide
    * a UI element which can then be re-shown by calling addToGroup
    *
    * @param id The id of the UI element to remove
    * @param groupId The id of the group to remove it from
    */
    public removeFromGroup(id, groupId): void;
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
    public watchVariable(title: string, object, property, ui?: string, group?: number, options?): number;
    public showObject(title, object, editable, group): number;
    /**
    * Registers a named path to an object so that the object can be referenced from another context for the creation of
    * watch UI
    *
    * @param {Object} object The object to stash
    * @param {String} path The path to use to access the object in the form "folder/folder/folder/item", for example
    * "actors/npcs/enemies/bots/ed209"
    * @returns The id of the stashed object - currently for internal use only
    */
    public stashObject(object, path): number;
    /**
    * Creates a DynamicUI manager and initialises it, registering against events.
    *
    * @param title
    * @returns {DynamicUIManager} The UI Manager
    */
    static create(): DynamicUIManager;
}
/**
* The instance of the DynamicUI manager
*/
declare var TurbulenzUI: DynamicUIManager;
interface GameSettings {
    width: number;
    height: number;
    boardSpacing: number;
    maxPlayers: number;
}
interface Food {
    x: number;
    y: number;
    updated: boolean;
    eatenBy: number;
}
interface GameState {
    PLAY: number;
    DEAD: number;
    ERROR: number;
}
declare class Game {
    public state: GameState;
    public currentState: number;
    public gameSession: GameSession;
    public hasChangedScore: boolean;
    public score: number;
    public kills: number;
    public myWormIndex: number;
    public gameStates: {
        (isHost: boolean, moveWorms: boolean): void;
    }[];
    public graphicsDevice: GraphicsDevice;
    public scoreIncrement: number;
    public gameSettings: GameSettings;
    public leaderboards: GameLeaderboards;
    public badges: GameBadges;
    public keyCodes: any;
    public mouseCodes: any;
    public foods: Food[];
    public worms: Worm[];
    public newWormDirection: number;
    public startTime: number;
    public join_error: string;
    public join_error_cb: () => void;
    public update(isHost, moveWorms): void;
    public checkCollisions(): void;
    public play(isHost, moveWorms): void;
    public dead(isHost): void;
    public scored(): void;
    public kill(): void;
    public died(killedBy): void;
    public placeFood(foodIndex): void;
    public placeWorm(wormIndex): void;
    public serialize(data): void;
    public serializeDelta(isHost, delta): boolean;
    public deserialize(isHost, data): boolean;
    public getFood(foodIndex): Food;
    public getWorm(wormIndex): Worm;
    public reset(): void;
    public start(): void;
    public onKeyDown(keynum): void;
    public onMouseDown(button): void;
    static create(gameSettings, graphicsDevice, gameSession, leaderboards, badges, keyCodes, mouseCodes): Game;
}
interface PlayerInfo {
    score: number;
    team: string;
    status: string;
    color: string;
}
interface WormDirection {
    NONE: number;
    UP: number;
    DOWN: number;
    RIGHT: number;
    LEFT: number;
}
declare class Worm {
    public direction: WormDirection;
    public upVector: number[];
    public downVector: number[];
    public rightVector: number[];
    public leftVector: number[];
    public zeroVector: number[];
    public boardSpacing: number;
    public boardWidth: number;
    public boardHeight: number;
    public maxPlayers: number;
    public directionVector: number[];
    public partsPositionX: any;
    public partsPositionY: any;
    public previousTailX: number;
    public previousTailY: number;
    public killedBy: number;
    public updated: boolean;
    public playerInfo: PlayerInfo;
    public hasLooped: boolean;
    public changeDirection(newDirection): void;
    public update(): void;
    public die(killedBy): void;
    public serialize(): {
        dir: any;
        x: any;
        y: any;
        score: number;
        color: string;
        team: string;
        status: string;
        killedBy: number;
    };
    public deserialize(isHost, data): void;
    public moveBody(): void;
    public moveHead(): void;
    public addToTail(): void;
    public isIntersectingSelf(): boolean;
    public isIntersecting(otherWorm): boolean;
    public containsPosition(x, y): boolean;
    public isOnHead(x, y): boolean;
    public reset(x, y): void;
    static create(gameSettings): Worm;
}
interface Technique2DParameters extends TechniqueParameters {
    clipSpace: any;
}
declare class Application {
    public hasShutDown: boolean;
    public runInEngine: boolean;
    public previousGameUpdateTime: number;
    public gameSession: GameSession;
    public userProfile: UserProfile;
    public multiplayerSession: MultiPlayerSession;
    public leaderboardManager: LeaderboardManager;
    public badgeManager: BadgeManager;
    public multiplayerSessionManager: MultiPlayerSessionManager;
    public leaderboards: GameLeaderboards;
    public badges: GameBadges;
    public htmlWriter: HtmlWriter;
    public appScene: AppScene;
    public game: Game;
    public devices: any;
    public managers: any;
    public others: any;
    public isHost: boolean;
    public connectionTime: number;
    public previousHeartbeatTime: number;
    public lastSentMessageTime: number;
    public frameCounter: number;
    public hostFrameCounter: number;
    public needToRender: boolean;
    public requestHandler: RequestHandler;
    public intervalID: number;
    public mappingTable: any;
    public font: Font;
    public technique2D: Technique;
    public technique2Dparameters: Technique2DParameters;
    public hasShutdown: boolean;
    public gameSettings: {
        width: number;
        height: number;
        boardSpacing: number;
        maxPlayers: number;
    };
    public gameTimeStep: number;
    public networkIds: {
        joining: number;
        update: number;
        leaving: number;
        ping: number;
        pong: number;
    };
    public heartbeatTime: number;
    public staleTime: number;
    public sceneSetup: boolean;
    public errorCallback(msg): void;
    public init(): void;
    public update(currentTime): boolean;
    public updateGame(gameStep, heartbeat): boolean;
    public render(currentTime): void;
    public loadUI(): void;
    public hasUILoaded(): boolean;
    public drawUI(): void;
    public hasShaderSupport(): boolean;
    public createDevices(): boolean;
    public enterCallbackChain(context, functions): void;
    public createGame(): void;
    public createInputDeviceCallbacks(): void;
    public createGameLeaderboards(callback): void;
    public createGameBadges(callback): void;
    public createHTMLWriter(): void;
    public startMultiplayerSession(): void;
    public createGameSession(callback): void;
    public createUserProfile(callback): void;
    public createMappingTable(callback): void;
    public createLeaderboardManager(callback): void;
    public createBadgeManager(): void;
    public createMultiplayerSessionManager(): void;
    public enterLoadingLoop(): void;
    public loadingStateLoop(): void;
    public connectingStateLoop(): void;
    public mainStateLoop(): void;
    public onMessage(senderID, messageType, messageData): void;
    public onJoiningMessage(senderID): void;
    public onUpdateMessage(senderID, messageData): void;
    public onLeavingMessage(senderID): void;
    public onPingMessage(senderID, messageData): void;
    public onPongMessage(senderID, messageData): void;
    public migrateHost(): void;
    public checkOthers(): void;
    public hasOthers(): boolean;
    public shutdown(): void;
    static create(runInEngine?: boolean): Application;
}
