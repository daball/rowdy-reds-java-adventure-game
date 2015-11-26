package edu.radford.rowdyred.internal;

import java.io.File;
import java.io.IOException;
import java.lang.reflect.*;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import javax.tools.JavaCompiler;
import javax.tools.JavaFileObject;
import javax.tools.StandardJavaFileManager;
import javax.tools.ToolProvider;

public class TabletCompilerService {
  public String phpSessionId;
  public String packageName;
  public String sourceCode;
  public Class compiledClass;
  public Object singletonInstance;

  /**
   * Source http://stackoverflow.com/questions/1554252/how-do-i-create-a-directory-within-the-current-working-directory-in-java
   **/
  public static File createDirectory(String directoryPath) throws IOException {
      File dir = new File(directoryPath);
      if (dir.exists()) {
          return dir;
      }
      if (dir.mkdirs()) {
          return dir;
      }
      throw new IOException("Failed to create directory '" + dir.getAbsolutePath() + "' for an unknown reason.");
  }


  public TabletCompilerService(String phpSessionId, Object phpGameState, String sourceCode) {
    this.phpSessionId = phpSessionId;
    this.packageName = "player_" + this.phpSessionId;
    this.sourceCode = ""
    + "package " + this.packageName + ";\n"
    + "\n"
    + "import edu.radford.rowdyred.behaviors.*;\n"
    + "import edu.radford.rowdyred.equipment.*;\n"
    + "import edu.radford.rowdyred.game.*;\n"
    + "import edu.radford.rowdyred.items.*;\n"
    + "import edu.radford.rowdyred.obstacles.*;\n"
    + "\n"
      + "public class Tablet /*extends Scope*/ {\n"
      + "\n"
      + "  public Tablet(/*Scope scope*/) {\n"
      + "    //copy Scope to Tablet\n"
      + "    /*this.me = scope.me;\n"
      + "    this.room = scope.room;\n"
      + "    this.leftHand = scope.leftHand;\n"
      + "    this.rightHand = scope.rightHand;\n"
      + "    this.backpack = scope.backpack;*/\n"
      + "  }\n"
      + "\n"
      + "  /* BEGIN PLAYER'S CODE */\n"
      + sourceCode
      + "  /* END PLAYER'S CODE */\n"
      + "}\n";//end class
  }

  public void compile() throws Exception {
    InlineCompiler.compile(this.packageName, "Tablet", this.sourceCode);
//    CompilerEngine engine = new CompilerEngine();
//    this.compiledClass = engine.compile(this.packageName, "Tablet", this.sourceCode);
//    Constructor ctor = this.compiledClass.getConstructor();
//    this.singletonInstance = ctor.newInstance();
  }

  public Object invoke(String methodName, Object[] parameters) throws Exception {
    if (this.compiledClass == null)
      this.compile();
    Method[] methods = compiledClass.getDeclaredMethods();
    for (int m = 0; m < methods.length; m++) {
        Method method = methods[m];
        if (method.getName() == methodName) {
            //System.out.println("Found main method in class.");
            return method.invoke(this.singletonInstance, parameters);
        }
    }
    return null;
  }
}
