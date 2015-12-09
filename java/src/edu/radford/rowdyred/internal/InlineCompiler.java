package edu.radford.rowdyred.internal;

import java.io.File;
import java.io.FileWriter;
import java.io.StringWriter;
import java.io.Writer;
import java.net.URL;
import java.net.URLClassLoader;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import javax.tools.Diagnostic;
import javax.tools.DiagnosticCollector;
import javax.tools.JavaCompiler;
import javax.tools.JavaFileObject;
import javax.tools.StandardJavaFileManager;
import javax.tools.ToolProvider;


public class InlineCompiler {
  
  private static URLClassLoader classLoader;

  public static void clean(String basePath, String packageName) {
    clean(Paths.get(basePath, packageName).toString());
  }
  
  public static void clean(String cleanPath) {
    File path = Paths.get(cleanPath).toFile();
    File[] c = path.listFiles();
    System.out.println("Cleaning out folder:" + path.toString());
    for (File file : c){
        if (file.isDirectory()){
            System.out.println("Deleting file:" + file.toString());
            clean(file.getPath());
            file.delete();
        } else {
            file.delete();
        }
    }
    path.delete();
  }

  public static Class<?> compile(String basePath, String classPath, String packageName, String className, String sourceCode) throws Exception {
    File sourceFile = Paths.get(basePath, packageName, className + ".java").toFile();
    String destDir = Paths.get(basePath).toString();
    if (sourceFile.getParentFile().exists() || sourceFile.getParentFile().mkdirs()) {
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

        DiagnosticCollector<JavaFileObject> diagnostics = new DiagnosticCollector<JavaFileObject>();
        JavaCompiler compiler = ToolProvider.getSystemJavaCompiler();
        //StandardJavaFileManager fileManager = compiler.getStandardFileManager(diagnostics, null, null);
        StandardJavaFileManager fileManager = compiler.getStandardFileManager(null, null, null);

        List<String> optionList = new ArrayList<String>();
        optionList.add("-d");
        optionList.add(destDir);
        optionList.add("-classpath");
        optionList.add(classPath + ":" + System.getProperty("java.class.path"));
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
        if (task.call()) {
          fileManager.close();
          classLoader = new URLClassLoader(new URL[]{
              new File(destDir).toURI().toURL(),
              new File(classPath).toURI().toURL()
              }, Thread.currentThread().getContextClassLoader());
          Class<?> loadedClass = classLoader.loadClass(packageName + "." + className);
          return loadedClass;
        } else {
          fileManager.close();
          for (Diagnostic<? extends JavaFileObject> diagnostic : diagnostics.getDiagnostics()) {
            output.append(String.format("Error on line %d: %s",
                diagnostic.getLineNumber(),
                diagnostic.getMessage(Locale.ENGLISH)));
          }
          throw new CompilationException("Failed to compile", output.toString());
        }
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

