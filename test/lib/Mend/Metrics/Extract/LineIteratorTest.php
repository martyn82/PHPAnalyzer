<?php
namespace Mend\Metrics\Extract;

class LineIteratorTest extends \TestCase {
	public function testIterator() {
		$lines = array(
			1 => 'Line 1',
			2 => 'Line 2',
			3 => 'Line 3',
			4 => 'Line 4',
			10 => 'Line 10'
		);

		$expectedIterationCount = count( $lines );
		$actualIterationCount = 0;

		$iterator = new LineIterator( $lines );
		$expectedLineNumber = 0;
		$expectedLine = null;

		foreach ( $iterator as $lineNumber => $line ) {
			$expectedLineNumber = key( $lines );
			$expectedLine = current( $lines );

			$this->assertEquals( $expectedLineNumber, $lineNumber );
			$this->assertEquals( $expectedLine, $line );

			next( $lines );
			$actualIterationCount++;
		}

		$this->assertEquals( $expectedIterationCount, $actualIterationCount );
	}
}