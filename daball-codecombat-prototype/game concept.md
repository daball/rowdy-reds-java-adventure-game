Game Concept:

Star Wars(r) meets Code Combat(r) to learn how to use the Java force.

A long time ago in a galaxy far away,
A lone Java developer came to play...

It is a period of learning for a young Skywalker.

user - javascript level user object (json) which describes the actual user's profile
World - world static methods
Avatar - Avatar class, and static method to unlock avatar

Initial game requirements:

  Guest registers a user account.
  Guest logs in as a registered user.
  First task:
    System.out.println("Let's play!");
      * Unlocks achievement: "Hello, stdout!"
        user.achievements["hello-stdout"] = true

Console then becomes available at all times to the user.
  when user.achievements["hello-stdout"]: engine.console = true

User is shown how to unlock first avatar via tutorial.

User is asked to unlock R2D2 via code console.

  Avatar.unlockNewAvatar("R2D2");
  
  * Unlocks achievement: "Using static methods."
  * Unlocks achievement: "Unlocked R2D2 avatar."
  
    user.achievements["static-methods"] = true
    user.achievements["avatar-r2d2"] = true
    
User is shown how to navigate worlds.

User is asked to enter warp to Tatooine via code console.

World.warpTo("Tatooine");

  * Unlocks achievement: "Unlocked Tatooine world."
  
    user.achievements["world-Tatooine"] = true

Screen then shows levels to complete on Tatooine.

User is shown how to enter a level.
  > Clicking on a level reveals its name.
  > Typing code in the console enters the level.

LEVEL 1: User is then asked to enter a level.

World.enterLevel("T1-simple-movement");

User is shown about variable declaration using:
  Avatar avatar = new Avatar("R2D2");

User is shown about avatar motion using:
  avatar.moveRight();
  avatar.moveUp();
  avatar.moveLeft();
  avatar.moveDown();

User is then asked to solve the puzzle using the learned task.

  Avatar avatar = new Avatar("R2D2");
  avatar.moveRight();
  avatar.moveRight();
  avatar.moveRight();
  avatar.moveRight();
  avatar.moveRight();

  * Unlocks achievement - "Simple movement."
  user.achievements["simple-movement"] = true

LEVEL 2: User is again asked to enter a level.

World.enterLevel("T2-specified-movement");

User is shown about variable movement by specifying the number of tiles to move:
  avatar.moveRight(3);
  avatar.moveUp(3);
  avatar.moveLeft(3);
  avatar.moveDown(3);

User is then asked to solve the puzzle using the learned task.

  //solve puzzle
  
  * Unlocks achievement - "Specified movement."
    user.achievements["specified-movement"]

LEVEL 3: User is shown about R2D2's ability to open doors by plugging into USB ports and hacking the system.

  Avatar r2d2 = new Avatar("R2D2");
  r2d2.moveUp();
  r2d2.connectToUSB();//displays console error if user (not r2d2) is not equipped with feature,
         //something comical like "my phone has recharged, but nothing else happened"
  r2d2.hackDoor("T3-Door-1"); //sets off alarm if not r2d2, clones run to destroy avatar

User is then asked to solve the puzzle using the learned task.

  * Unlocks achievement - "Hacking through locked doors."
    user.achievements["hacking-doors"] = true
    
Upon solving the puzzle, we find Anakin.

  * Unlocks achievement - "Unlocked Anakin Skywalker avatar."

