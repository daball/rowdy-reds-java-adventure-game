package edu.radford.rowdyred.internal;

import java.io.File;
import java.io.IOException;
import java.lang.reflect.*;
import java.util.concurrent.Callable;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;
import java.util.concurrent.TimeUnit;
import java.util.concurrent.TimeoutException;

public class TabletCompilerService {
  public String phpSessionId;
  public String cachePath;
  public String classPath;
  public String packageName;
  public String sourceCode;
  public Class<?> compiledClass;
  public Object tabletInstance;
  public ConsoleOutputCapturer outputCapturer;
  public String consoleOutput = "";

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
    this.outputCapturer = new ConsoleOutputCapturer();
  }
  
  public void clean() {
    InlineCompiler.clean(this.cachePath, this.packageName);
  }
  
  public String getConsoleOutput() {
    return this.consoleOutput;
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
      + "public class Tablet {"
      + sourceCode
      + "public ChessBoard chessBoard;"
      + "public Dragon dragon;"
      + "public Player me;"
      + "public Salve healingSalve;"
      + "public Weapon sword;"
      + "public Weapon magicSword;"
      + "public Shield shield;"
      + "public Weapon crossbow;"
      + "public String key = \"efsdg908hn3rv0tyobri7oirgfoli\";"
      + "public Portcullis portcullis;"
      + "public Crank crank;"
      + "public Handle handle;"
      + "public Combination safe;"
      + "public int c1, c2, c3;"
      + "public Tablet(/*Scope scope*/) {"
      + constructorCode
      + "  }"
      + ""
      + "}";//end class
    
    this.compiledClass = InlineCompiler.compile(this.cachePath, this.classPath, this.packageName, "Tablet", this.sourceCode);
    this.tabletInstance = this.compiledClass.newInstance();
//    CompilerEngine engine = new CompilerEngine();
//    this.compiledClass = engine.compile(this.packageName, "Tablet", this.sourceCode);
//    Constructor ctor = this.compiledClass.getConstructor();
//    this.singletonInstance = ctor.newInstance();
  }

  public Object invokeMethod(String methodName, final Object[] parameters) throws Throwable {
    Method[] methods = this.compiledClass.getDeclaredMethods();
    String methodsAvailable = "";
    for (int m = 0; m < methods.length; m++) {
        final Method method = methods[m];
        if (method.isAccessible())
          methodsAvailable += "tablet." + method.getName() + "()\n";
        if (method.getName().equals(methodName)) {
          ExecutorService executor = Executors.newSingleThreadExecutor();
          Future<Object> future = executor.submit(new Callable<Object>() {
              @Override
              public Object call() throws Exception {
                outputCapturer.start();
                try {
                  Object done = method.invoke(tabletInstance, parameters);
                  System.out.flush();
                  System.err.flush();
                  consoleOutput = outputCapturer.stop();
                  return done;
                } catch (InvocationTargetException e) {
                  if (e.getCause() != null)
                    throw((Exception)e.getCause());
                  else
                    throw e;
                } catch (Exception e) {
                  throw e;
                }
              }
            });

          try {
            Object val = future.get(750, TimeUnit.MILLISECONDS);
            executor.shutdownNow();
            return val;
          } catch (TimeoutException e) {
              System.out.flush();
              System.err.flush();
              consoleOutput = outputCapturer.stop();
              future.cancel(true);
              executor.shutdownNow();
              throw(e);
          } catch (ExecutionException e) {
            System.out.flush();
            System.err.flush();
            consoleOutput = outputCapturer.stop();
            future.cancel(true);
            executor.shutdownNow();
            if (e.getCause() != null)
              throw(e.getCause());
            throw (e);
          } catch (Exception e) {
            System.out.flush();
            System.err.flush();
            consoleOutput = outputCapturer.stop();
            future.cancel(true);
            executor.shutdownNow();
            throw(e);
          }

        }
    }
    return "Method name " + methodName + " not found in class " + this.compiledClass.getSimpleName() + ". Accessible methods:\n" + methodsAvailable;
  }
  
  public Object getField(String fieldName) throws IllegalArgumentException, IllegalAccessException, NoSuchFieldException, SecurityException {
    return this.compiledClass.getField(fieldName).get(this.tabletInstance);
  }
}
