<?php
namespace Mend\Collections;

class ArrayListTest extends \TestCase {
	public function testTypeSafetySuccess() {
		$item = new Item();
		$array = new ItemArray();

		$array[] = $item;
		self::assertEquals( $item, $array[ 0 ] );
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testTypeSafetyFail() {
		$item = new \stdClass();
		$array = new ItemArray();

		$array[] = $item;
		self::fail( 'Failed; no exception thrown.' );
	}
}

class Item {
}

class ItemArray extends ArrayList {
	/**
	 * @see ArrayList::offsetSet()
	 */
	public function offsetSet( $offset, $item ) {
		array_map(
			function ( Item $item ) use ( $offset ) {
				parent::offsetSet( $offset, $item );
			},
			array( $item )
		);
	}
}
