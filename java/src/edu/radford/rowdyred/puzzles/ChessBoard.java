package edu.radford.rowdyred.puzzles;

import java.util.Arrays;

public class ChessBoard implements Solvable {
	
	boolean ranks[] = { false,false,false,false,false,false,false,false };
	
	public void stepOnRank(int rank) {
		ranks[rank-1] = true;
	}
	
	public int isSolved () {
		boolean solved[] = { true,true,true,true,true,true,true,true };
		if (Arrays.equals(ranks, solved))
		  return 1;
		return 0;
	}
}
