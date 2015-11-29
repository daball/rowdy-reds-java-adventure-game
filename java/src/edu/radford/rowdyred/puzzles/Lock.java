package edu.radford.rowdyred.puzzles;

public class Lock {

	private String secret;
	private boolean locked;
	
	public Lock(String secret) {
		this.secret = secret;
	}
	
	public void unlock(Key key) {
		if (key.matchKey(secret))
			locked = false;
	}
	
	public boolean isLocked() {
		return locked;
	}
}
