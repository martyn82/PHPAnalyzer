<?php
namespace Mend\Source\Code\Location;

class LocationTest extends \TestCase {
	public function testAccessors() {
		$i = 0;

		while ( $i < 100 ) {
			$line = mt_rand( 1, PHP_INT_MAX );
			$column = mt_rand( 1, PHP_INT_MAX );

			$location = new Location( $line, $column );
			self::assertEquals( $line, $location->getLine() );
			self::assertEquals( $column, $location->getColumn() );

			$i++;
		}
	}

	public function testEmpty() {
		$location = Location::createEmpty();
		self::assertNull( $location->getLine() );
		self::assertNull( $location->getColumn() );
	}

	public function testToString() {
		$line = mt_rand( 1, PHP_INT_MAX );
		$column = mt_rand( 1, PHP_INT_MAX );

		$location = new Location( $line, $column );
		self::assertEquals( "({$line},{$column})", (string) $location );
	}

	public function testEmptyToString() {
		$location = Location::createEmpty();
		self::assertEquals( "(0,0)", (string) $location );
	}
}