import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.atomic.AtomicReference;

import org.json.JSONArray;
import org.json.JSONObject;

public class SessionStore {
    
    /**
     * Holds actions that will be applied to avatar(s) in the game
     * engine. These actions are the result of having executed
     * player code against the API compute server.
     */
    public static ThreadLocal<JSONArray> avatarActions = new ThreadLocal<JSONArray>() {
        @Override
        protected JSONArray initialValue() {
            return new JSONArray();
        }
    };
    
    /**
     * Holds the current map state. Must be imported from the 
     * game engine client prior to rendering the code.
     */
    public static ThreadLocal<JSONArray> currentMap = new ThreadLocal<JSONArray>() {
        @Override
        protected JSONArray initialValue() {
            return new JSONArray();
        }
    };

    /**
     * Holds the current map state. Must be imported from the 
     * game engine client prior to rendering the code.
     */
    public static ThreadLocal<JSONObject> gameState = new ThreadLocal<JSONObject>() {
        @Override
        protected JSONObject initialValue() {
            return new JSONObject();
        }
    };
    
//    public static List<JSONObject> codeBranches = new ArrayList<JSONObject>();
//    
//    public static void startNewCodeBranch(JSONObject branchObject) {
//        branchObject.put("actions", new JSONArray());
//        codeBranches.add(branchObject);
//    }
//
//    public static void closeMostRecentCodeBranch() {
//        JSONObject branchObject = codeBranches.remove(codeBranches.size()-1);
//        if (codeBranches.isEmpty())
//            actions.put(branchObject);
//        else
//            codeBranches.get(codeBranches.size()-1).append("actions", branchObject);
//    }
//    
//    public static void addAction(Object object) {
//        if (codeBranches.isEmpty())
//            actions.put(object);
//        else
//            codeBranches.get(codeBranches.size()-1).append("actions", object);
//    }
    
    public static String getAvatarActionsAsJson() {
        return avatarActions.get().toString(2);
    }
}
