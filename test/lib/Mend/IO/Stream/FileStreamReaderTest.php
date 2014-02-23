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
	protected function getInstance( array $methods = array(), File $file = null ) {
		if ( is_null( $file ) ) {
			$name = $this->getProtocol() . '/tmp/foo';

			$file = $this->getMock(
				'\Mend\IO\FileSystem\File',
				array( 'getName' ),
				array( $name )
			);

			$file->expects( self::any() )
				->method( 'getName' )
				->will( self::returnValue( $name ) );
		}

		return $this->getMock(
			'\Mend\IO\Stream\FileStreamReader',
			$methods,
			array( $file )
		);
	}

	public function testConstructor() {
		$file = $this->getFile();
		$reader = new FileStreamReader( $file );

		self::assertNotNull( $reader );
	}

	public function testReaderNeverWritable() {
		$reader = $this->getInstance();
		self::assertFalse( $reader->isWritable() );
	}

	public function testFile() {
		$reader = $this->getInstance( array( 'isClosed', 'isReadable' ) );

		$reader->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( false ) );

		$reader->expects( self::any() )
			->method( 'isReadable' )
			->will( self::returnValue( true ) );

		$reader->open();
		$reader->read();
		$reader->close();
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamNotReadableException
	 */
	public function testFileNonExistent() {
		$reader = $this->getInstance( array( 'isReadable' ) );

		$reader->expects( self::any() )
			->method( 'isReadable' )
			->will( self::returnValue( false ) );

		$reader->read();

		self::fail( "Read from a non-existent file." );
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamClosedException
	 */
	public function testClosedStream() {
		$reader = $this->getInstance( array( 'isClosed', 'isReadable' ) );

		$reader->expects( self::any() )
			->method( 'isReadable' )
			->will( self::returnValue( true ) );

		$reader->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$reader->read();
	}

	public function testOpen() {
		$name = $this->getProtocol() . '/tmp/foo';

		$file = $this->getMock(
			'\Mend\IO\FileSystem\File',
			array( 'getName' ),
			array( $name ),
			'',
			false
		);

		$file->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $name ) );

		$reader = new FileStreamReader( $file );

		self::assertFalse( $reader->isOpen() );

		$reader->open();

		self::assertTrue( $reader->isOpen() );
	}

	public function testOpenAlreadyOpen() {
		$reader = $this->getInstance( array( 'isOpen' ) );

		$reader->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( true ) );

		$reader->open();
	}

	/**
	 * @expectedException \Mend\IO\IOException
	 */
	public function testOpenFailed() {
		$reader = $this->getInstance( array( 'isOpen' ) );

		$reader->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( false ) );

		\FileSystem::setFOpenResult( false );
		@$reader->open(); // suppress warning of failed call to stream_open()

		self::fail( "Unexpected: Stream should not be able to open." );
	}

	public function testClose() {
		$reader = $this->getInstance( array( 'isClosed' ) );

		$reader->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( false ) );

		$reader->open();
		$reader->close();
	}

	public function testCloseAlreadyClosed() {
		$reader = $this->getInstance( array( 'isClosed' ) );
		$reader->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$reader->close();
	}
}