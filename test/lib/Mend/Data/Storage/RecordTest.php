<?php
namespace Mend\Data\Storage;

use Mend\Collections\Map;

class RecordTest extends \TestCase {
	public function testAccessors() {
		$fields = new Map();
		$fields->set( 'id', 1893 );
		$fields->set( 'name', 'foo' );

		$record = new Record( $fields );

		self::assertEquals( 1893, $record->getValue( 'id' ) );
		self::assertEquals( 'foo', $record->getValue( 'name' ) );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testEmptyFields() {
		$record = new Record( new Map() );
		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidField() {
		$fields = new Map( array( 'id' => 1 ) );
		$record = new Record( $fields );
		$record->getValue( 'foo' );
	}
}
