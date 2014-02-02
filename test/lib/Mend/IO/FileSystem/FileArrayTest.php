<?php
namespace Mend\IO\FileSystem;

class FileArrayTest extends \TestCase {
	public function testAddFile() {
		$array = new FileArray();
		$file = new File( '/tmp/file' );
		$array[] = $file;
		self::assertEquals( $file, $array[ 0 ] );
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAddInvalidItem() {
		$array = new FileArray();
		$item = new \stdClass();
		$array[] = $item;
		self::fail( "Expected exception" );
	}
}