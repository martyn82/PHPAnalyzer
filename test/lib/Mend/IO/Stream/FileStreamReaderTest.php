<?php
namespace Mend\IO\Stream;

require_once "FileStreamTest.php";

use Mend\IO\FileSystem\File;

class FileStreamReaderTest extends FileStreamTest {
	/**
	 * Retrieves a mocked reader.
	 *
	 * @param array $methods
	 * @param File $file
	 *
	 * @return FileStreamReader
	 */
	private function getReader( array $methods = array(), File $file = null ) {
		if ( is_null( $file ) ) {
			$file = $this->getMock( '\Mend\IO\FileSystem\File', array(), array(), '', false );
		}

		return $this->getMock(
			'\Mend\IO\Stream\FileStreamReader',
			$methods,
			array( $file )
		);
	}

	public function testConstructor() {
		$file = new File( '/tmp/file' );
		$reader = new FileStreamReader( $file );

		self::assertNotNull( $reader );
	}

	public function testReaderNeverWritable() {
		$reader = $this->getReader();
		self::assertFalse( $reader->isWritable() );
	}

	public function testRead() {
		$reader = $this->getReader( array( 'isClosed' ) );

		$reader->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( false ) );

		self::$isReadableResult = true;
		self::$isResourceResult = true;

		$reader->read();
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamNotReadableException
	 */
	public function testReadFileNonExistent() {
		$reader = $this->getReader( array( 'isReadable' ) );
		$reader->expects( self::any() )
			->method( 'isReadable' )
			->will( self::returnValue( false ) );

		$reader->read();

		self::fail( "Read from a non-existent file." );
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamClosedException
	 */
	public function testReadClosedStream() {
		$reader = $this->getReader( array( 'isClosed', 'isReadable' ) );

		$reader->expects( self::any() )
			->method( 'isReadable' )
			->will( self::returnValue( true ) );

		$reader->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$reader->read();
	}

	public function testOpen() {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array(), array(), '', false );
		$reader = new FileStreamReader( $file );

		self::$isResourceResult = false;
		self::$fopenResult = true;

		self::assertFalse( $reader->isOpen() );

		$reader->open();
		self::$isResourceResult = true;

		self::assertTrue( $reader->isOpen() );
	}

	public function testOpenAlreadyOpen() {
		$reader = $this->getReader( array( 'isOpen' ) );

		$reader->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( true ) );

		$reader->open();
	}

	/**
	 * @expectedException \Mend\IO\IOException
	 */
	public function testOpenFailed() {
		$reader = $this->getReader( array( 'isOpen' ) );
		$reader->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( false ) );

		self::$fopenResult = false;

		$reader->open();

		self::fail( "Unexpected: Stream should not be able to open." );
	}

	public function testClose() {
		$reader = $this->getReader( array( 'isClosed' ) );
		$reader->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( false ) );

		$reader->close();
	}

	public function testCloseAlreadyClosed() {
		$reader = $this->getReader( array( 'isClosed' ) );
		$reader->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$reader->close();
	}
}