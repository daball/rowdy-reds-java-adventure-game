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

    public String cachePath = "_cache";
    
    public String generateFolderName(int length) {
        final String validChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        char[] chars = new char[length];
        for (int i=0; i < length; i++)
        {
            chars[i] = validChars.charAt((int)Math.floor(Math.random()*(double)validChars.length()));
        }
        return cachePath + "/" + new String(chars);
    }
    
//    public String saveJavaFile(String javaClassName, String javaSourceCode) {
//        Path path = Path.get(cachePath, generateFolderName(12), javaClassName + ".java");
//    }
    
    /**
     * Source http://stackoverflow.com/questions/4463440/compile-java-source-code-from-a-string
     * @param javaClassName
     * @param javaSourceCode
     * @return
     * @throws Exception 
     */
    public String compileAndRunJavaAndRenderJson(String javaClassName, String javaSourceCode) throws Exception {
        String compilationPath = generateFolderName(12) + "/";
        
        if (compiler == null) throw new Exception( "Compiler unavailable");
        JavaSourceFromString file = new JavaSourceFromString(javaClassName, javaSourceCode);
        Iterable<? extends JavaFileObject> compilationUnits = Arrays.asList(file);

        DiagnosticCollector<JavaFileObject> diagnostics = new DiagnosticCollector<JavaFileObject>();
        StandardJavaFileManager fileManager = compiler.getStandardFileManager(diagnostics, null, null);
        
        List<String> options = new ArrayList<String>();
        options.add("-d");
        options.add(compilationPath);
        options.add( "-classpath");
        URLClassLoader urlClassLoader = (URLClassLoader)Thread.currentThread().getContextClassLoader();
        
        StringWriter output = new StringWriter();
        JavaCompiler.CompilationTask task = compiler.getTask(output, fileManager, diagnostics, null, null, compilationUnits);
        
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
