<?php
namespace Mend\IO\FileSystem;

class DirectoryTest extends \TestCase {
	public function testNewDirectory() {
		$directory = new Directory( '/tmp' );
		self::assertTrue( $directory instanceof Directory );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testNewDirectoryInvalid() {
		$directory = new Directory( '' );
		self::fail( "Expected exception." );
	}

	public function testLocation() {
		$location = '/tmp';
		$directory = new Directory( $location );
		self::assertEquals( $location, $directory->getName() );
	}

	public function testBasename() {
		$location = '/tmp/foo/bar';
		$directory = new Directory( $location );
		self::assertEquals( 'bar', $directory->getBaseName() );
	}

	public function testDirectoryToString() {
		$location = '/tmp';
		$directory = new Directory( $location );
		self::assertEquals( $location, (string) $directory );
	}

	public function testDirectoryExists() {
		$location = '/tmp';
		$directory = new Directory( $location );
		self::assertTrue( $directory->exists() );
	}

	public function testDirectoryIsDirectory() {
		$location = '/tmp';
		$directory = new Directory( $location );
		self::assertTrue( $directory->isDirectory() );
	}

	public function testDirectoryIsFile() {
		$location = '/tmp';
		$directory = new Directory( $location );
		self::assertFalse( $directory->isFile() );
	}
}