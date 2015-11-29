package edu.radford.rowdyred.internal;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.io.StringWriter;
import java.io.Writer;
import java.net.URL;
import java.net.URLClassLoader;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import javax.tools.Diagnostic;
import javax.tools.DiagnosticCollector;
import javax.tools.JavaCompiler;
import javax.tools.JavaFileObject;
import javax.tools.StandardJavaFileManager;
import javax.tools.ToolProvider;

import org.junit.experimental.theories.Theories;


public class InlineCompiler {
  
  public static Class<?> compile(String basePath, String classPath, String packageName, String className, String sourceCode) throws Exception {
//    throw new Exception(System.getProperty("java.class.path"));
    File sourceFile = Paths.get(basePath, packageName, className + ".java").toFile();
    String destDir = Paths.get(basePath).toString();
    if (sourceFile.getParentFile().exists() || sourceFile.getParentFile().mkdirs()) {
//      try {
        Writer writer = null;
        try {
          writer = new FileWriter(sourceFile);
          writer.write(sourceCode);
          writer.flush();
        } finally {
          try {
            writer.close();
          } catch (Exception e) {
          }
        }

        /** Compilation Requirements *********************************************************************************************/
        DiagnosticCollector<JavaFileObject> diagnostics = new DiagnosticCollector<JavaFileObject>();
        JavaCompiler compiler = ToolProvider.getSystemJavaCompiler();
        //StandardJavaFileManager fileManager = compiler.getStandardFileManager(diagnostics, null, null);
        StandardJavaFileManager fileManager = compiler.getStandardFileManager(null, null, null);

        // This sets up the class path that the compiler will use.
        // I've added the .jar file that contains the DoStuff interface within in it...
        List<String> optionList = new ArrayList<String>();
        optionList.add("-d");
        optionList.add(destDir);
        optionList.add("-classpath");
        optionList.add(classPath + ":" + System.getProperty("java.class.path"));//+ ";dist/InlineCompiler.jar");
//
        Iterable<? extends JavaFileObject> compilationUnit
            = fileManager.getJavaFileObjects(sourceFile);
        StringWriter output = new StringWriter();
        JavaCompiler.CompilationTask task = compiler.getTask(
          output,
          fileManager,
          diagnostics,
          optionList,
          null,
          compilationUnit);
        /********************************************************************************************* Compilation Requirements **/
        if (task.call()) {
          fileManager.close();
          /** Load and execute *************************************************************************************************/
          // Create a new custom class loader, pointing to the directory that contains the compiled
          // classes, this should point to the top of the package structure!
          URLClassLoader classLoader = new URLClassLoader(new URL[]{
              new File(destDir).toURI().toURL(),
              new File(classPath).toURI().toURL()
              }, Thread.currentThread().getContextClassLoader());
          // Load the class from the classloader by name....
          Class<?> loadedClass = classLoader.loadClass(packageName + "." + className);
//          Class<?> loadedClass = Class.forName(packageName + "." + className);
          return loadedClass;
          // Create a new instance...
//          Object obj = loadedClass.newInstance();
          // Santity check
          // if (obj instanceof DoStuff) {
          //   // Cast to the DoStuff interface
          //   DoStuff stuffToDo = (DoStuff)obj;
          //   // Run it baby
          //   stuffToDo.doStuff();
          // }
          /************************************************************************************************* Load and execute **/
        } else {
          fileManager.close();
          for (Diagnostic<? extends JavaFileObject> diagnostic : diagnostics.getDiagnostics()) {
            output.append(String.format("Error on line %d in %s%n",
                diagnostic.getLineNumber(),
                diagnostic.getSource().toUri()));
          }
          throw new CompilationException("Failed to compile: " + sourceFile.toString(), output.toString());
        }
//        fileManager.close();
//      } catch (IOException | ClassNotFoundException /*| InstantiationException | IllegalAccessException*/ exp) {
//        exp.printStackTrace();
//      }
    }
    return null;
  }

}

final class CompilationException extends RuntimeException {

  private static final long serialVersionUID = 1L;

  public CompilationException(String message, String output) {
      super(message + "; caused by:\n\n" + output);
  }
}

