# Game Application API

# `GameEngine` class

The `GameEngine` class contains these instance fields:

- `$gameState` : `GameState` object
- `$commandProcessor` : `CommandProcessor` object


The `GameEngine` class contains these instance methods:

- `createSession()` : creates a new `$gameState` and `$_SESSION['gameState']` (do not call directly)
- `restoreSession()` : restores `$_SESSION['gameState']` to `$gameState` (do not call directly)
- `saveSession()` : saves $gameState to `$_SESSION['gameState']` (do not call directly)

The `GameEngine` class contains these constructors:

- `__construct()` : starts up PHP session; restores session if it exists, otherwise creates session; starts the command processor, which executes any commands on the `$_POST['commandLine']`; saves the session

# `CommandProcessor` class

The `CommandProcessor` class contains these instance fields:

- `$commandHandlers` : array of `CommandHandlerInterface` implementation classes
- `$commandInput` : string containing command input from `$_POST['commandLine']`
- `$commandOutput` : string containing command output after executing the command handler against `$commandInput`

The `CommandProcessor` class contains these instance methods:

- `dispatchCommandLine($gameState, $commandLine)` : validates `$commandLine` against each command handler until it finds a valid command handler for the command (by calling `CommandHandlerInterface->validateCommand($gameState, $commandLine)`); when a valid command handler is found, it dispatches the command to the command handler (by calling `CommandHandlerInterface->executeCommand($gameState, $commandLine)`); when it is not found, it returns an error

The `CommandProcessor` class contains these constructors:

-- `__construct()` : initializes all `CommandHandlerInterface` classes, processes `$_POST['commandLine']` input if it exists (saves to `$commandInput`), saves command output to `$commandOutput`

# `CommandHandlerInterface` class

This class is intended to be extended and overridden. The default implementation returns `false` on `validateCommand($gameState, $commandLine)` and retuns an empty string (`''`) on `executeCommand($gameState, $commandLine)`. Implementers should implement both of these methods. The interface should contain, at a minimum, these instance methods:

- `validateCommand($gameState, $commandLine)` : called from `CommandProcessor->dispatchCommandLine($gameState, $commandLine)` to ask the command handler if the `$commandLine` is valid for this command handler
- `executeCommand($gameState, $commandLine)` : only called after validation, called called from `CommandProcessor->dispatchCommandLine($gameState, $commandLine)` to execute the `$commandLine` using this command handler

# `CommandHandlerInterface` implementations

The following classes extend `CommandHandlerInterface`:

- NavigateCommandHandler : responds to `$commandLine` inputs such as `north`, `south`, `east`, and `west`
- HelpCommandHandler : responds to `$commandLine` inputs such as `help` and `?`
- ResetCommandHandler : responds to `$commandLine` inputs such as `reset` and `restart`
- ExitCommandHandler : responds to `$commandLine` inputs such as `exit` and `System.exit(0);`

# `GameState` class

A `GameState` instance holds and manipulates all the data about the current game state, including the map, player's location, console history, number of moves, and whether or not the game is exiting.

The `GameState` class contains these instance fields, and is (un)/serializable.

- `$map` : current `Map` instance
- `$avatarLocation` : current `Room->name` for the player's location in the `$map`
- `$consoleHistory` : string with all the command line input and output (for display to the screen)
- `$moves` : number of moves the player has made
