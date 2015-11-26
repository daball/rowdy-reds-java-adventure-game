package edu.radford.rowdyred.internal;

import java.io.File;
import java.io.IOException;
import java.io.StringWriter;
import java.lang.reflect.Method;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLClassLoader;
import java.nio.file.FileSystem;
import java.nio.file.Files;
import java.nio.file.Path;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import javax.tools.DiagnosticCollector;
import javax.tools.JavaCompiler;
import javax.tools.JavaFileObject;
import javax.tools.StandardJavaFileManager;
import javax.tools.ToolProvider;

//snagged from earlier prototype

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

    /**
     * Source http://stackoverflow.com/questions/4463440/compile-java-source-code-from-a-string
     * @param javaClassName
     * @param javaSourceCode
     * @return
     * @throws Exception
     */
    public Class compile(String packageName, String javaClassName, String javaSourceCode) throws Exception {
        String compilationPath = "/tmp/" + packageName + "/";
        createDirectory(compilationPath);

        if (compiler == null) throw new Exception( "Compiler unavailable");
        JavaSourceFromString file = new JavaSourceFromString(javaClassName, javaSourceCode);
        Iterable<? extends JavaFileObject> compilationUnits = Arrays.asList(file);

        DiagnosticCollector<JavaFileObject> diagnostics = new DiagnosticCollector<JavaFileObject>();
        StandardJavaFileManager fileManager = compiler.getStandardFileManager(diagnostics, null, null);

        List<String> options = new ArrayList<String>();
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
            // try {
                File clsFolder = new File("/tmp/");
                URL url = clsFolder.toURL();
                URL[] urls = new URL[]{url};
                ClassLoader cl = new URLClassLoader(urls);
                return cl.loadClass(packageName + "." + javaClassName);

//                System.out.println("Found " + methods.length + " methods in class.");
//            } catch (MalformedURLException e) {
//                // TODO Auto-generated catch block
//                e.printStackTrace();
            // } catch (ClassNotFoundException e) {
                // TODO Auto-generated catch block
//                e.printStackTrace();
            // }
        }

        return null;
    }

}
