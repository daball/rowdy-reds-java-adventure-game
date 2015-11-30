package edu.radford.rowdyred.internal;

import java.io.File;
import java.io.IOException;
import java.io.OutputStream;
import java.lang.reflect.*;
import java.nio.file.Paths;

public class TabletCompilerService {
  public String phpSessionId;
  public String cachePath;
  public String classPath;
  public String packageName;
  public String sourceCode;
  public Class<?> compiledClass;
  public Object tabletInstance;

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


  public TabletCompilerService(String phpSessionId, String cachePath, String classPath, Object phpGameState) {
    this.phpSessionId = phpSessionId;
    this.cachePath = cachePath;
    this.classPath = classPath;
    this.packageName = "player_" + this.phpSessionId;
  }

  public void compile(String constructorCode, String sourceCode) throws Exception {
    //Wrap sourceCode
    this.sourceCode = sourceCode = ""
    + "package " + this.packageName + ";"
    + ""
//    + "import edu.radford.rowdyred.behaviors.*;\n"
//    + "import edu.radford.rowdyred.equipment.*;\n"
//    + "import edu.radford.rowdyred.game.*;\n"
//    + "import edu.radford.rowdyred.items.*;\n"
//    + "import edu.radford.rowdyred.obstacles.*;\n"
    + "import edu.radford.rowdyred.puzzles.*;"
    + ""
      + "public class Tablet /*extends Scope*/ {"
      + sourceCode
      + "\n"
      + "public ChessBoard chessBoard;"
      + "public Dragon dragon;"
      + "public Player me;"
      + "public Salve salve;"
      + "public Weapon sword;"
      + "public Shield shield;"
      + "public Weapon crossbow;"
      + "public String key = \"efsdg908hn3rv0tyobri7oirgfoli\";"
      + "public Portcullis portcullis;"
      + "public Crank crank;"
      + "public Handle handle;"
//      + "  private "//replace setout
      + "  public Tablet(/*Scope scope*/) {"
      + constructorCode
      + "  }"
      + ""
//      + "  /* BEGIN PLAYER'S CODE */\n"
//      + sourceCode
//      + "  /* END PLAYER'S CODE */\n"
      + "}";//end class
    
    this.compiledClass = InlineCompiler.compile(this.cachePath, this.classPath, this.packageName, "Tablet", this.sourceCode);
    this.tabletInstance = this.compiledClass.newInstance();
//    CompilerEngine engine = new CompilerEngine();
//    this.compiledClass = engine.compile(this.packageName, "Tablet", this.sourceCode);
//    Constructor ctor = this.compiledClass.getConstructor();
//    this.singletonInstance = ctor.newInstance();
  }

  public Object invokeMethod(String methodName, Object[] parameters) throws Exception {
    Method[] methods = this.compiledClass.getDeclaredMethods();
    String methodsAvailable = "";
    for (int m = 0; m < methods.length; m++) {
        Method method = methods[m];
        if (method.isAccessible())
          methodsAvailable += "tablet." + method.getName() + "()\n";
        if (method.getName().equals(methodName)) {
            return method.invoke(this.tabletInstance, parameters);
        }
    }
    return "Method name " + methodName + " not found in class " + this.compiledClass.getSimpleName() + ". Accessible methods:\n" + methodsAvailable;
  }
  
  public Object getField(String fieldName) throws IllegalArgumentException, IllegalAccessException, NoSuchFieldException, SecurityException {
    return this.compiledClass.getField(fieldName).get(this.tabletInstance);
  }
}
