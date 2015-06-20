import org.json.JSONObject;


/**
 * Avatar class contains actions that the gamer avatar can perform,
 * regardless of map. These actions, when executed, output a JSON
 * array of objects with key/value pairs, which can be executed
 * in the game engine itself.
 * 
 * The game engine will be responsible for determining whether or
 * not the actions output from the compiled code relate to success
 * or failure on part of the user.
 * 
 * This class simply outputs actions.
 * 
 * @author David Ball <daball@email.radford.edu>
 *
 */
public class Avatar {
    public String name = "";
    public Avatar () {
    }
    public Avatar (String name) {
        this.name = name;
    }
    public String moveLeft(int steps) {
        JSONObject object = new JSONObject();
        object.put("avatar", this.name);
        object.put("action", "move");
        object.put("direction", "left");
        object.put("steps", steps);
        ActionStore.actions.put(object);
        return object.toString();
    }
    public String moveRight(int steps) {
        JSONObject object = new JSONObject();
        object.put("avatar", this.name);
        object.put("action", "move");
        object.put("direction", "right");
        object.put("steps", steps);
        ActionStore.actions.put(object);
        return object.toString();
    }
    public String moveUp(int steps) {
        JSONObject object = new JSONObject();
        object.put("avatar", this.name);
        object.put("action", "move");
        object.put("direction", "up");
        object.put("steps", steps);
        ActionStore.actions.put(object);
        return object.toString();
    }
    public String moveDown(int steps) {
        JSONObject object = new JSONObject();
        object.put("avatar", this.name);
        object.put("action", "move");
        object.put("direction", "down");
        object.put("steps", steps);
        ActionStore.actions.put(object);
        return object.toString();
    }
    public String moveLeft() { return moveLeft(1); }
    public String moveRight() { return moveRight(1); }
    public String moveUp() { return moveUp(1); }
    public String moveDown() { return moveDown(1); }
}
