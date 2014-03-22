<?php
namespace Mend\Data;

class DataObjectCollectionTest extends \TestCase {
	public function testAdd() {
		$totalCount = mt_rand( 0, PHP_INT_MAX );
		$collection = new DataObjectCollection( $totalCount );

		self::assertEquals( $totalCount, $collection->getTotalCount() );

		$object = $this->getMock( '\Mend\Data\DataObject' );
		$collection->add( $object );

		self::assertEquals( 1, $collection->size() );
	}

	public function testTotalCount() {
		$collection = new DataObjectCollection();
		self::assertEquals( 0, $collection->getTotalCount() );

		$collection->add( $this->getMock( '\Mend\Data\DataObject' ) );
		self::assertEquals( 1, $collection->getTotalCount() );
	}

	/**
	 * @expectedException \PHPUnit_Framework_Error
	 */
	public function testAddInvalidObject() {
		$collection = new DataObjectCollection();

		$object = new \stdClass();
		$collection->add( $object );
	}
}
