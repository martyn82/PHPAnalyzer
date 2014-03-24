<?php
namespace Mend\IO\FileSystem;

use Mend\IO\FileSystem\File;

class FileTest extends \TestCase {
	public function setUp() {
		\FileSystem::resetResults();
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	public function testNewFileValid() {
		$file = new File( 'test:///tmp/some' );
		self::assertTrue( $file instanceof File );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testNewFileInvalid() {
		$file = new File( '' );
		self::fail( "Test should have triggered an exception." );
	}

	public function testFileName() {
		$location = 'test:///tmp/file';
		$file = new File( $location );
		self::assertEquals( $location, $file->getName() );
	}

	public function testFileExtension() {
		$location = 'test:///tmp/file';
		$file = new File( $location );
		self::assertEquals( '', $file->getExtension() );

		$location = 'test:///tmp/file.txt';
		$file = new File( $location );
		self::assertEquals( 'txt', $file->getExtension() );

		$location = 'test:///tmp/file.default.properties';
		$file = new File( $location );
		self::assertEquals( 'properties', $file->getExtension() );
	}

	public function testFileToString() {
		$location = 'test:///tmp/file';
		$file = new File( $location );
		self::assertEquals( $location, (string) $file );
	}

	public function testFileExists() {
		$location = 'test:///tmp';
		$file = new File( $location );
		self::assertTrue( $file->exists() );
	}

	public function testFileIsDirectory() {
		\FileSystem::setStatModeResult( \FileSystem::FILE_MODE );

		$location = 'test:///tmp';
		$file = new File( $location );
		self::assertFalse( $file->isDirectory() );
	}

	public function testFileIsFile() {
		\FileSystem::setStatModeResult( \FileSystem::FILE_MODE );

		$location = 'test:///tmp';
		$file = new File( $location );
		self::assertTrue( $file->isFile() );
	}

	public function testDelete() {
		$location = 'test:///tmp/file.foo';
		$file = new File( $location );
		$file->delete();

		self::assertTrue( true );
	}
}