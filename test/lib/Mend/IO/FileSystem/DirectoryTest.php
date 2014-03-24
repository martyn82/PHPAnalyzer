<?php
namespace Mend\IO\FileSystem;

class DirectoryTest extends \TestCase {
	public function setUp() {
		\FileSystem::resetResults();
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	public function testNewDirectory() {
		$directory = new Directory( 'test:///tmp' );
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
		$location = 'test:///tmp';
		$directory = new Directory( $location );
		self::assertEquals( $location, $directory->getName() );
	}

	public function testBasename() {
		$location = 'test:///tmp/foo/bar';
		$directory = new Directory( $location );
		self::assertEquals( 'bar', $directory->getBaseName() );
	}

	public function testDirectoryToString() {
		$location = 'test:///tmp';
		$directory = new Directory( $location );
		self::assertEquals( $location, (string) $directory );
	}

	public function testDirectoryExists() {
		$location = 'test:///tmp';
		$directory = new Directory( $location );
		self::assertTrue( $directory->exists() );
	}

	public function testDirectoryIsDirectory() {
		\FileSystem::setStatModeResult( \FileSystem::DIR_MODE );

		$location = 'test:///tmp';
		$directory = new Directory( $location );
		self::assertTrue( $directory->isDirectory() );
	}

	public function testDirectoryIsFile() {
		$location = 'test:///tmp';
		$directory = new Directory( $location );
		self::assertFalse( $directory->isFile() );
	}

	public function testIterator() {
		$location = 'test:///tmp';
		$directory = new Directory( $location );
		$iterator = $directory->iterator();
		self::assertInstanceOf( '\DirectoryIterator', $iterator );
	}

	public function testDelete() {
		$location = 'test:///tmp';
		$directory = new Directory( $location );
		$directory->delete();
		self::assertTrue( true );
	}
}