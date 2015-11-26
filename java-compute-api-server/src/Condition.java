import org.json.JSONObject;

/**
 * Generates JSON data for an if (expression) condition.
 * 
 * @author David Ball <daball@email.radford.edu>
 */
public class Condition {
    
//    public void startIfBranch(String condition) {
//        JSONObject ifObject = new JSONObject();
//        ifObject.put("action", "if");
//        ActionStore.startNewCodeBranch(ifObject);
//        
//        JSONObject conditionObject = new JSONObject();
//        conditionObject.put("action", "condition");
//        conditionObject.put("condition", condition);
//        ActionStore.startNewCodeBranch(conditionObject);
//    }
//    
//    public void startElseIfBranch(String condition) {
//        //close out the last if/else if condition branch
//        ActionStore.closeMostRecentCodeBranch();
//        
//        //create a new condition branch
//        JSONObject conditionObject = new JSONObject();
//        conditionObject.put("action", "condition");
//        conditionObject.put("condition", condition);
//        ActionStore.startNewCodeBranch(conditionObject);
//    }
//    
//    public void startElseBranch() {
//        //close out the last if/else if condition branch
//        ActionStore.closeMostRecentCodeBranch();
//        
//        //create a new condition branch
//        JSONObject conditionObject = new JSONObject();
//        conditionObject.put("action", "condition");
//        conditionObject.put("condition", true); //else is always true, when executed
//        ActionStore.startNewCodeBranch(conditionObject);
//    }
//    
//    public void endIfBranch() {
//        //close out the last if/else if condition branch
//        ActionStore.closeMostRecentCodeBranch();
//        
//        //close out the last if main branch
//        ActionStore.closeMostRecentCodeBranch();
//    }
//    
}