import java.io.BufferedInputStream;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;

public class ConsoleApp {

    public static void main(String[] args) {
        String sampleCode = "";//sample source code for void main()
        
        File file = new File("../sample-data/sample-java.java");
        FileInputStream fis = null;
        BufferedInputStream bis = null;
        DataInputStream dis = null;
     
        try {
          fis = new FileInputStream(file);
     
          // Here BufferedInputStream is added for fast reading.
          bis = new BufferedInputStream(fis);
          dis = new DataInputStream(bis);
     
          // dis.available() returns 0 if the file does not have more lines.
          while (dis.available() != 0) {
     
          // this statement reads the line from the file and print it to
            // the console.
            sampleCode += "\t\t" + dis.readLine() + "\n";
          }
     
          // dispose all the resources after using them.
          fis.close();
          bis.close();
          dis.close();
     
        } catch (FileNotFoundException e) {
          e.printStackTrace();
        } catch (IOException e) {
          e.printStackTrace();
        }
        
        CompilerEngine engine = new CompilerEngine();
        try {
            engine.compileAndRunJavaAndRenderJson("Sample",
                      "public class Sample {"
                    + "  public static void main(String args[]) {"
                    + sampleCode
                    + "    System.out.println(ActionStore.toJSON());"
                    + "  }"
                    + "}");
        } catch (Exception e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

}
