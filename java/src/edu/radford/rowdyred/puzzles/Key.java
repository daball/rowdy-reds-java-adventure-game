package edu.radford.rowdyred.puzzles;

public class Key {

	private String secret;
	
	public Key(String secret) {
		super();
		this.secret = secret;
	}
	
	public boolean matchKey(String secret) {
		return secret.equals(this.secret);
	}
}
