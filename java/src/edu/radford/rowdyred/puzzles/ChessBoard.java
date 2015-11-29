package edu.radford.rowdyred.puzzles;

import java.util.Arrays;

public class ChessBoard {
	
	boolean ranks[] = { false,false,false,false,false,false,false,false };
	
	public void stepOnRank(int rank) {
		ranks[rank-1] = true;
	}
	
	public boolean isSolved () {
		boolean solved[] = { true,true,true,true,true,true,true,true };
		return Arrays.equals(ranks, solved);
	}
}
