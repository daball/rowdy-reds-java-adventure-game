module app.domain {
  export interface IGamesList {
    games?: string[];
    error?: any;
  }

  export interface IGameInProgress {
    gameName?: string;
    roomName?: string;
    imageUrl?: string;
    consoleHistory?: string;
    commandHistory?: string[];
    player?: IPlayer;
    logger?: string[];
    tabletCode?: string;
    eol?: string;
    prompt?: string;
    moves?: number;
    isExiting?: boolean;
    showManual?: boolean;
    error?: any;
  }

  export interface IPlayer {
    leftHand?: string;
    rightHand?: string;
    equipment?: string[];
  }

  export class GameInProgress implements IGameInProgress {
    constructor(public gameName: string,
                public roomName: string,
                public imageUrl: string,
                public consoleHistory: string,
                public commandHistory: string[],
                public eol: string,
                public prompt: string,
                public moves: number,
                public isExiting: boolean,
                public error: any) {
    }
  }

  export class GamesList implements IGamesList {
    constructor(public games: string[],
                public error: any) {

    }
  }
}
