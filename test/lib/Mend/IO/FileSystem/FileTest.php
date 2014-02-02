<?php
namespace Mend\IO\FileSystem;

use Mend\IO\FileSystem\File;

class FileTest extends \TestCase {
	public function testNewFileValid() {
		$file = new File( '/tmp/some' );
		self::assertTrue( $file instanceof File );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testNewFileInvalid() {
		$file = new File( '' );
		self::fail( "Exception expected." );
	}

	public function testFileName() {
		$location = '/tmp/file';
		$file = new File( $location );
		self::assertEquals( $location, $file->getName() );
	}

	public function testFileExtension() {
		$location = '/tmp/file';
		$file = new File( $location );
		self::assertEquals( '', $file->getExtension() );

		$location = '/tmp/file.txt';
		$file = new File( $location );
		self::assertEquals( 'txt', $file->getExtension() );

		$location = '/tmp/file.default.properties';
		$file = new File( $location );
		self::assertEquals( 'properties', $file->getExtension() );
	}

	public function testFileToString() {
		$location = '/tmp/file';
		$file = new File( $location );
		self::assertEquals( $location, (string) $file );
	}
}