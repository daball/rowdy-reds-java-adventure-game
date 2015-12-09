package edu.radford.rowdyred.internal;

import java.io.FileDescriptor;
import java.net.InetAddress;
import java.security.Permission;

/**
 * Overwrites default security properties in order to try to make
 * sure game players cannot access anything out of the ordinary
 * while running arbitrary Java code on the system server.
 *  
 * @author David Ball <daball@email.radford.edu>
 */
public class TabletSecurityManager extends SecurityManager {

  public TabletSecurityManager() {
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkPermission(java.security.Permission)
   */
  @Override
  public void checkPermission(Permission perm) {
    // TODO Auto-generated method stub
    super.checkPermission(perm);
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkPermission(java.security.Permission, java.lang.Object)
   */
  @Override
  public void checkPermission(Permission perm, Object context) {
    // TODO Auto-generated method stub
    super.checkPermission(perm, context);
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkCreateClassLoader()
   */
  @Override
  public void checkCreateClassLoader() {
    super.checkCreateClassLoader();
    throw new SecurityException("Creating a class loader on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkAccess(java.lang.Thread)
   */
  @Override
  public void checkAccess(Thread t) {
    super.checkAccess(t);
    throw new SecurityException("Accessing thread properties on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkAccess(java.lang.ThreadGroup)
   */
  @Override
  public void checkAccess(ThreadGroup g) {
    super.checkAccess(g);
    throw new SecurityException("Accessing thread group properties on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkExit(int)
   */
  @Override
  public void checkExit(int status) {
    super.checkExit(status);
    throw new SecurityException("exitVM is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkExec(java.lang.String)
   */
  @Override
  public void checkExec(String cmd) {
    super.checkExec(cmd);
    throw new SecurityException("Running commands on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkLink(java.lang.String)
   */
  @Override
  public void checkLink(String lib) {
    super.checkLink(lib);
    throw new SecurityException("Accessing links on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkRead(java.io.FileDescriptor)
   */
  @Override
  public void checkRead(FileDescriptor fd) {
    super.checkRead(fd);
    throw new SecurityException("Reading files on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkRead(java.lang.String)
   */
  @Override
  public void checkRead(String file) {
    super.checkRead(file);
    throw new SecurityException("Reading files on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkRead(java.lang.String, java.lang.Object)
   */
  @Override
  public void checkRead(String file, Object context) {
    super.checkRead(file, context);
    throw new SecurityException("Reading files on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkWrite(java.io.FileDescriptor)
   */
  @Override
  public void checkWrite(FileDescriptor fd) {
    super.checkWrite(fd);
    throw new SecurityException("Writing files on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkWrite(java.lang.String)
   */
  @Override
  public void checkWrite(String file) {
    super.checkWrite(file);
    throw new SecurityException("Writing files on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkDelete(java.lang.String)
   */
  @Override
  public void checkDelete(String file) {
    super.checkDelete(file);
    throw new SecurityException("Deleting files on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkConnect(java.lang.String, int)
   */
  @Override
  public void checkConnect(String host, int port) {
    super.checkConnect(host, port);
    throw new SecurityException("Connecting to network resources on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkConnect(java.lang.String, int, java.lang.Object)
   */
  @Override
  public void checkConnect(String host, int port, Object context) {
    super.checkConnect(host, port, context);
    throw new SecurityException("Connecting to network resources on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkListen(int)
   */
  @Override
  public void checkListen(int port) {
    super.checkListen(port);
    throw new SecurityException("Listening for incoming connections on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkAccept(java.lang.String, int)
   */
  @Override
  public void checkAccept(String host, int port) {
    super.checkAccept(host, port);
    throw new SecurityException("Accepting connections on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkMulticast(java.net.InetAddress)
   */
  @Override
  public void checkMulticast(InetAddress maddr) {
    super.checkMulticast(maddr);
    throw new SecurityException("Using multicast connections on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkMulticast(java.net.InetAddress, byte)
   */
  @SuppressWarnings("deprecation")
  @Override
  public void checkMulticast(InetAddress maddr, byte ttl) {
    super.checkMulticast(maddr, ttl);
    throw new SecurityException("Using multicast connections on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkPropertiesAccess()
   */
  @Override
  public void checkPropertiesAccess() {
    super.checkPropertiesAccess();
    throw new SecurityException("Accessing system properties on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkPropertyAccess(java.lang.String)
   */
  @Override
  public void checkPropertyAccess(String key) {
    super.checkPropertyAccess(key);
    throw new SecurityException("Accessing system properties on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkTopLevelWindow(java.lang.Object)
   */
  @SuppressWarnings("deprecation")
  @Override
  public boolean checkTopLevelWindow(Object window) {
    /*return*/ super.checkTopLevelWindow(window);
    throw new SecurityException("Accessing top-level window on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkPrintJobAccess()
   */
  @Override
  public void checkPrintJobAccess() {
    super.checkPrintJobAccess();
    throw new SecurityException("Accessing print jobs on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkSystemClipboardAccess()
   */
  @SuppressWarnings("deprecation")
  @Override
  public void checkSystemClipboardAccess() {
    super.checkSystemClipboardAccess();
    throw new SecurityException("Accessing system clipboard on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkAwtEventQueueAccess()
   */
  @SuppressWarnings("deprecation")
  @Override
  public void checkAwtEventQueueAccess() {
    super.checkAwtEventQueueAccess();
    throw new SecurityException("Accessing AWT event queue on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkPackageAccess(java.lang.String)
   */
  @Override
  public void checkPackageAccess(String pkg) {
    super.checkPackageAccess(pkg);
    if (! ((  !pkg.startsWith("edu.radford.rowdyred.behaviors")
        || !pkg.startsWith("edu.radford.rowdyred.equipment")
        || !pkg.startsWith("edu.radford.rowdyred.game")
        || !pkg.startsWith("edu.radford.rowdyred.items")
        || !pkg.startsWith("edu.radford.rowdyred.obstacles")
        || !pkg.startsWith("edu.radford.rowdyred.puzzles"))
        && (!pkg.endsWith(".tests")) ) )
      throw new SecurityException("Accessing packages outside edu.radford.rowdyred.* on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkPackageDefinition(java.lang.String)
   */
  @Override
  public void checkPackageDefinition(String pkg) {
    super.checkPackageDefinition(pkg);
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkSetFactory()
   */
  @Override
  public void checkSetFactory() {
    super.checkSetFactory();
    throw new SecurityException("Setting socket or stream factory on the server is not allowed.");
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkMemberAccess(java.lang.Class, int)
   */
  @SuppressWarnings("deprecation")
  @Override
  public void checkMemberAccess(Class<?> clazz, int which) {
    super.checkMemberAccess(clazz, which);
    //I don't think there is any threat here.
  }

  /* (non-Javadoc)
   * @see java.lang.SecurityManager#checkSecurityAccess(java.lang.String)
   */
  @Override
  public void checkSecurityAccess(String target) {
    super.checkSecurityAccess(target);
    //I don't think there is any threat here.
  }

}
