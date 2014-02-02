<?php
namespace Mend\Collections;

class MapTest extends \TestCase {
	public function testSet() {
		$map = new Map();
		self::assertEquals( 0, $map->getSize() );

		$map->set( 'key', 'value' );
		self::assertEquals( 'value', $map->get( 'key' ) );
	}

	public function testSetOverwrites() {
		$map = new Map();
		self::assertEquals( 0, $map->getSize() );

		$map->set( 'key', 'value' );
		self::assertEquals( 1, $map->getSize() );
		self::assertEquals( 'value', $map->get( 'key' ) );

		$map->set( 'key', 'value2' );
		self::assertEquals( 1, $map->getSize() );
		self::assertEquals( 'value2', $map->get( 'key' ) );
	}

	public function testAddAll() {
		$add = array(
			'one' => '1',
			'two' => '2',
			'three' => '3',
			'four' => '4'
		);

		$map = new Map();
		$map->addAll( $add );

		foreach ( $add as $key => $value ) {
			self::assertEquals( $value, $map->get( $key ) );
		}
	}

	public function testHasKey() {
		$map = new Map();
		self::assertFalse( $map->hasKey( 'key' ) );

		$map->set( 'key', 12 );
		self::assertTrue( $map->hasKey( 'key' ) );
	}
}