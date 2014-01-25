<?php
namespace Mend\IO\Stream;

use Mend\IO\FileSystem\File;

class FileStreamReaderTest extends \TestCase {
	public function testNewFileReader() {
		$file = new File( '/tmp/file' );
		$reader = new FileStreamReader( $file );
		self::assertNotNull( $reader );
	}

	/**
	 * @expectedException Mend\IO\Stream\StreamNotReadableException
	 */
	public function testReadFileNonExistent() {
		$file = new File( '/tmp/' . uniqid( 'test_' ) );
		$reader = new FileStreamReader( $file );
		$reader->read();
		self::fail( "Read from a non-existent file." );
	}
}