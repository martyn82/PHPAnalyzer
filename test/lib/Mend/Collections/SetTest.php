<?php
namespace Mend\Collections;

class SetTest extends \TestCase {
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testAddDuplicates() {
		$set = new DummySet();
		$set->add( 'foo' );
		$set->add( 'foo' );
	}

	public function testAddObjects() {
		$set = new DummySet();
		$set->add( new \stdClass() );
		$set->add( new \stdClass() );

		self::assertEquals( 2, $set->size() );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testAddDuplicateObjects() {
		$set = new DummySet();
		$obj = new \stdClass();
		$set->add( $obj );
		$set->add( $obj );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testAddAllDuplicates() {
		$set = new DummySet();
		$set->add( 'foo' );

		$setB = new DummySet();
		$setB->add( 'bar' );
		$setB->add( 'foo' );

		$set->addAll( $setB );
	}
}

class DummySet extends AbstractSet {}
