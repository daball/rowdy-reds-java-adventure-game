package edu.radford.rowdyred.puzzles.tests;

import static org.junit.Assert.*;

import org.junit.Test;

import edu.radford.rowdyred.puzzles.ChessBoard;

public class ChessBoardTest {

	@Test
	public void testStepOnRank() {
		ChessBoard board = new ChessBoard();
		
		for (int i = 1; i <= 8; i++) {
			assertEquals(0, board.isSolved());
			board.stepOnRank(i);
		}
		assertEquals(1, board.isSolved());
	}

}
