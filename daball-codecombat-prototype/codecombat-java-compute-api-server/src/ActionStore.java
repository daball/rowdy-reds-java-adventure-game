import org.json.JSONArray;

public class ActionStore {
    
    public static JSONArray actions = new JSONArray();
    
    public static String toJSON() {
        return actions.toString(2);
    }
}
