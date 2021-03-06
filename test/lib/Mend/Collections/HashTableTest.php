<?php
namespace Mend\Collections;

class HashTableTest extends \TestCase {
	public function testAddToBucket() {
		$hashTable = new StringHashTable();
		$hashTable->add( 0, 'string1' );
		$hashTable->add( 0, 'string2' );

		self::assertEquals( 2, count( $hashTable[ 0 ] ) );
		self::assertEquals( 'string1', $hashTable[ 0 ][ 0 ] );
		self::assertEquals( 'string2', $hashTable[ 0 ][ 1 ] );

		$hashTable[ 1 ] = array( 'string1', 'string2' );
		self::assertEquals( 2, count( $hashTable[ 1 ] ) );
		self::assertEquals( 'string1', $hashTable[ 1 ][ 0 ] );
		self::assertEquals( 'string2', $hashTable[ 1 ][ 1 ] );

		$hashTable[ 2 ] = 'string1';
		self::assertEquals( 1, count( $hashTable [ 2 ] ) );
		self::assertEquals( 'string1', $hashTable[ 2 ][ 0 ] );
	}

	public function testRemoveFromBucket() {
		$hashTable = new StringHashTable();
		$hashTable->add( 0, 'string1' );
		$hashTable->add( 0, 'string2' );

		unset( $hashTable[ 0 ][ 0 ] ); // should unset the element at index 0 of the bucket with index 0

		self::assertArrayNotHasKey( 0, $hashTable[ 0 ] );
		self::assertEquals( 1, count( $hashTable[ 0 ] ) );
		self::assertEquals( 'string2', $hashTable[ 0 ][ 1 ] );
	}

	public function testConstructor() {
		$hashTableArray = array(
			'hash1' => array( 'string1', 'string2' ),
			'hash2' => array(),
			'hash3' => array( 'value1' )
		);

		$hashTable = new StringHashTable( $hashTableArray );

		self::assertEquals( $hashTableArray[ 'hash1' ], $hashTable[ 'hash1' ] );
		self::assertEquals( $hashTableArray[ 'hash2' ], $hashTable[ 'hash2' ] );
		self::assertEquals( $hashTableArray[ 'hash3' ], $hashTable[ 'hash3' ] );
	}
}

// example implementation
class StringHashTable extends HashTable {}
