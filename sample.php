<?php
class Some {
	/* complexity : 5 */
	function foo() {
		switch ( $y ) {
			case 1:
				break;
				
			case 2:
				break;
		}
		
		if ( true ) {
			$x = 0;
		}
		else {
			$x = 1;
		}
		
		while ( $x ) {
			$x += 1;
		}
		
		try {
			$bar();
		}
		catch ( \Exception $e ) {
			
		}
	}
	
	function bar() {
		try {
			$bar();
		}
		catch ( \Exception $e ) {
				
		}
	}
	
	function baz() {
		try {
			$bar();
		}
		catch ( \Exception $e ) {
	
		}
	}
}