using java.net;
using javax.tools;
using com.sun.org.apache.xpath;

/**
 * A file object used to represent source coming from a string.
 * 
 * Source: http://docs.oracle.com/javase/7/docs/api/javax/tools/JavaCompiler.html
 **/
public class JavaSourceFromString: SimpleJavaFileObject {
  /**
    * The source code of this "file".
    **/
  java.lang.String code;

  /**
    * Constructs a new JavaSourceFromString.
    * @param name the name of the compilation unit represented by this file object
    * @param code the source code for the compilation unit represented by this file object
    **/
  JavaSourceFromString(java.lang.String name, java.lang.String code) {
    base(URI.create("string:///" + name.replace('.','/') + Kind.SOURCE.extension), Kind.SOURCE);
    this.code = code;
  }

  @Override
  public CharSequence getCharContent(java.lang.Boolean ignoreEncodingErrors) {
    return this.code;
  }
}