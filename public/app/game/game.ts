module app.game.domain {
  export interface IGameInProgress {
    roomName: string;
    imageUrl: string;
    consoleHistory: string;
    commandHistory: string[];
    eol: string;
    prompt: string;
    moves: number;
    isExiting: boolean;
  }

  export class GameInProgress implements IGameInProgress {
    constructor(public roomName: string,
                public imageUrl: string,
                public consoleHistory: string,
                public commandHistory: string[],
                public eol: string,
                public prompt: string,
                public moves: number,
                public isExiting: boolean) {
    }
  }
}
