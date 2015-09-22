using java.io;
using java.lang;
using java.net;
using java.nio.file;
using java.util;
using javax.tools;

/**
 * CompilerEngine is responsible for taking input Java code
 * and compiling it, running it, and outputting
 * JSON data for RU Code Combat.
 * 
 * @author David Ball <daball@email.radford.edu>
 *
 */
public class CompilerEngine {
  private JavaCompiler compiler = ToolProvider.getSystemJavaCompiler();

  public java.lang.String cachePath = "_cache";

  public java.lang.String generateFolderName(int length) {
    const java.lang.String validChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    java.lang.Character[] chars = new java.lang.Character[length];
    for (int i=0; i < length; i++)
    {
      chars[i] = validChars.charAt((int)Math.floor(Math.random()*(double)validChars.length()));
    }
    return "./" + cachePath + "/" + new String(chars);
  }

  /**
     * Source http://stackoverflow.com/questions/4463440/compile-java-source-code-from-a-string
     * @param javaClassName
     * @param javaSourceCode
     * @return
     * @throws Exception 
     */
  public java.lang.String compileAndRunJavaAndRenderJson(java.lang.String javaClassName, java.lang.String javaSourceCode) {
    java.lang.String compilationPath = generateFolderName(12) + "/";
    new java.io.File(compilationPath).mkdirs();

    if (compiler == null) throw new java.lang.Exception( "Compiler unavailable");
    JavaSourceFromString file = new JavaSourceFromString(javaClassName, javaSourceCode);
    java.util.Iterable<JavaFileObject> compilationUnits = Arrays.asList(file);

    DiagnosticCollector<JavaFileObject> diagnostics = new DiagnosticCollector<JavaFileObject>();
    StandardJavaFileManager fileManager = compiler.getStandardFileManager(diagnostics, null, null);

    java.util.List<String> options = new java.util.ArrayList<String>();
    options.add("-d");
    options.add(compilationPath);
    //options.add( "-classpath");
    URLClassLoader urlClassLoader = (URLClassLoader)Thread.currentThread().getContextClassLoader();

    StringWriter output = new StringWriter();
    JavaCompiler.CompilationTask task = compiler.getTask(output, fileManager, diagnostics, options, null, compilationUnits);

    boolean success = task.call();
    try {
      fileManager.close();
    } catch (IOException e) {
      // TODO Auto-generated catch block
      e.printStackTrace();
    }

    //System.out.println("Success: " + success);

    if (success) {
      try {
        File clsFolder = new File("./");
        URL url = clsFolder.toURL();
        URL[] urls = new URL[]{url};
        ClassLoader cl = new URLClassLoader(urls);
        Class cls = cl.loadClass(javaClassName);

        Method[] methods = cls.getDeclaredMethods();
        for (int m = 0; m < methods.length; m++) {
          Method method = methods[m];
          if (method.getName() == "main") {
            //System.out.println("Found main method in class.");
            method.invoke(null, new Object[] { new String[0] });
          }
        }
        //System.out.println("Found " + methods.length + " methods in class.");
      } catch (MalformedURLException e) {
        // TODO Auto-generated catch block
        e.printStackTrace();
      } catch (ClassNotFoundException e) {
        // TODO Auto-generated catch block
        e.printStackTrace();
      }
    }

    return "";
  }

}