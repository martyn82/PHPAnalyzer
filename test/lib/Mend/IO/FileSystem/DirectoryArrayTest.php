<?php
namespace Mend\IO\FileSystem;

class DirectoryArrayTest extends \TestCase {
	public function testAddDirectory() {
		$array = new DirectoryArray();
		$directory = new Directory( '/tmp' );
		$array[] = $directory;
		self::assertEquals( $directory, $array[ 0 ] );
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAddInvalidItem() {
		$array = new DirectoryArray();
		$item = new \stdClass();
		$array[] = $item;
		self::fail( "Expected exception" );
	}
}