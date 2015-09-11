[API Docs](./) &raquo; CommandHandlers

# `CommandHandlerInterface` class

![CommandHandlerInterface diagram](./img/CommandHandlerInterface.gif)

This class is intended to be extended and overridden. The default implementation returns `false` on `validateCommand($gameState, $commandLine)` and retuns an empty string (`''`) on `executeCommand($gameState, $commandLine)`. Implementers should implement both of these methods. The interface should contain, at a minimum, these instance methods:

- `validateCommand($gameState, $commandLine)` : called from `CommandProcessor->dispatchCommandLine($gameState, $commandLine)` to ask the command handler if the `$commandLine` is valid for this command handler
- `executeCommand($gameState, $commandLine)` : only called after validation, called called from `CommandProcessor->dispatchCommandLine($gameState, $commandLine)` to execute the `$commandLine` using this command handler

# `CommandHandlerInterface` implementations

The following classes extend `CommandHandlerInterface`:

- NavigateCommandHandler : responds to `$commandLine` inputs such as `north`, `south`, `east`, and `west`

![NavigateCommandHandler diagram](./img/NavigateCommandHandler.gif)

- HelpCommandHandler : responds to `$commandLine` inputs such as `help` and `?`

![HelpCommandHandler diagram](./img/HelpCommandHandler.gif)

- ResetCommandHandler : responds to `$commandLine` inputs such as `reset` and `restart`

![ResetCommandHandler diagram](./img/ResetCommandHandler.gif)

- ExitCommandHandler : responds to `$commandLine` inputs such as `exit` and `System.exit(0);`

![ExitCommandHandler diagram](./img/ExitCommandHandler.gif)

# How CommandHandlers Are Used In `CommandProcessor` class

![CommandProcessor class diagram](./img/6_CommandProcessor_Class_Diagram.gif)
