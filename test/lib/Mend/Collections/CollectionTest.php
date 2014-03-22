<?php
namespace Mend\Collections;

class CollectionTest extends \TestCase {
	public function testAdditionRemovalContains() {
		$collection = new DummyCollection();

		self::assertEquals( 0, $collection->size() );
		self::assertTrue( $collection->isEmpty() );
		self::assertEquals( array(), $collection->toArray() );

		$collection->add( 'foo' );

		self::assertEquals( 1, $collection->size() );
		self::assertEquals( array( 'foo' ), $collection->toArray() );

		$otherCollection = new DummyCollection();
		$otherCollection->add( 'bar' );
		$otherCollection->add( 'baz' );

		$collection->addAll( $otherCollection );

		self::assertEquals( 3, $collection->size() );
		self::assertEquals( array( 'foo', 'bar', 'baz' ), $collection->toArray() );

		self::assertFalse( $collection->isEmpty() );
		self::assertTrue( $collection->contains( 'foo' ) );
		self::assertTrue( $collection->contains( 'bar' ) );
		self::assertTrue( $collection->contains( 'baz' ) );
		self::assertTrue( $collection->containsAll( $otherCollection ) );

		$collection->remove( 'bar' );

		self::assertTrue( $collection->contains( 'foo' ) );
		self::assertFalse( $collection->contains( 'bar' ) );
		self::assertTrue( $collection->contains( 'baz' ) );
		self::assertFalse( $collection->containsAll( $otherCollection ) );

		$collection->removeAll( $otherCollection );
		self::assertFalse( $collection->isEmpty() );

		$collection->remove( 'non' );
		self::assertFalse( $collection->isEmpty() );

		$collection->remove( 'foo' );
		self::assertTrue( $collection->isEmpty() );
	}

	public function testRetain() {
		$collection = new DummyCollection();
		$collection->add( 'foo' );

		$otherCollection = new DummyCollection();
		$otherCollection->add( 'bar' );
		$otherCollection->add( 'baz' );

		$collection->addAll( $otherCollection );

		self::assertEquals( array( 'foo', 'bar', 'baz' ), $collection->toArray() );

		$collection->retainAll( $otherCollection );

		self::assertEquals( $otherCollection->toArray(), $collection->toArray() );
	}

	public function testIterators() {
		$iterators = $this->iteratorProvider();
		$iterator1 = $iterators[ 0 ][ 0 ];
		$iterator2 = $iterators[ 1 ][ 0 ];

		self::assertEquals( $iterator2, $iterator1->iterator() );
	}

	/**
	 * @dataProvider iteratorProvider
	 *
	 * @param \Iterator $iterator
	 * @param array $expected
	 */
	public function testIterator( \Iterator $iterator, array $expected ) {
		$actual = array();
		foreach ( $iterator as $value ) {
			$actual[] = $value;
		}
		self::assertEquals( $expected, $actual );

		$actual = array();
		$iterator->rewind();
		while ( $iterator->valid() ) {
			$actual[ $iterator->key() ] = $iterator->current();
			$iterator->next();
		}
		self::assertEquals( $expected, $actual );
	}

	public function iteratorProvider() {
		$values = array( 'foo', 'bar', 'baz' );
		$collection = $this->createCollection( $values );

		return array(
			array( $collection, $values ),
			array( $collection->iterator(), $values )
		);
	}

	private function createCollection( array $values ) {
		$collection = new DummyCollection();

		foreach ( $values as $value ) {
			$collection->add( $value );
		}

		return $collection;
	}
}

class DummyCollection extends AbstractCollection {}
