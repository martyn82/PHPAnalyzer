<?php
namespace Mend\IO\Stream;

require_once "FileStreamTest.php";

use Mend\IO\FileSystem\File;

class FileStreamWriterTest extends FileStreamTest {
	public function testStreamInterface() {
		$streamWriter = $this->getMockBuilder( '\Mend\IO\Stream\StreamWriter' )
			->setMethods( array( 'open', 'close', 'write', 'isOpen', 'isClosed', 'isReadable', 'isWritable' ) )
			->setConstructorArgs( array() )
			->getMock();

		$streamWriter->expects( self::once() )->method( 'open' );
		$streamWriter->expects( self::once() )->method( 'close' );
		$streamWriter->expects( self::exactly( 4 ) )->method( 'write' );

		$linesToWrite = array(
			'Line 1',
			'Line 2',
			'Line 3',
			'Line 4'
		);

		$streamWriter->open();

		foreach ( $linesToWrite as $line ) {
			$streamWriter->write( $line );
		}

		$streamWriter->close();
	}

	protected function getInstance( array $methods = array(), File $file = null ) {
		if ( is_null( $file ) ) {
			$file = $this->getFile();
		}

		return $this->getMockBuilder( '\Mend\IO\Stream\FileStreamWriter' )
			->setMethods( $methods )
			->setConstructorArgs( array( $file ) )
			->getMock();
	}

	public function testConstructor() {
		$file = $this->getFile();
		$writer = new FileStreamWriter( $file );

		self::assertNotNull( $writer );
	}

	public function testWriterNeverReadable() {
		$writer = $this->getInstance();
		self::assertFalse( $writer->isReadable() );
	}

	public function testFile() {
		$writer = $this->getInstance( array( 'isClosed', 'isWritable' ) );

		$writer->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( false ) );

		$writer->expects( self::any() )
			->method( 'isWritable' )
			->will( self::returnValue( true ) );

		$writer->open();
		$writer->write( 'foo' );
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamNotWritableException
	 */
	public function testFileNonExistent() {
		$writer = $this->getInstance( array( 'isWritable' ) );

		$writer->expects( self::any() )
			->method( 'isWritable' )
			->will( self::returnValue( false ) );

		$writer->write( 'foo' );

		self::fail( "Write to a non-existent file." );
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamClosedException
	 */
	public function testClosedStream() {
		$writer = $this->getInstance( array( 'isClosed', 'isWritable' ) );

		$writer->expects( self::any() )
			->method( 'isWritable' )
			->will( self::returnValue( true ) );

		$writer->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$writer->write( 'foo' );
	}

	public function testOpen() {
		$file = $this->getFile();
		$writer = new FileStreamWriter( $file );

		\FileSystem::setFOpenResult( true );

		self::assertFalse( $writer->isOpen() );

		$writer->open();

		self::assertTrue( $writer->isOpen() );
	}

	public function testOpenAlreadyOpen() {
		$writer = $this->getInstance( array( 'isOpen' ) );

		$writer->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( true ) );

		$writer->open();
	}

	/**
	 * @expectedException \Mend\IO\IOException
	 */
	public function testOpenFailed() {
		$writer = $this->getInstance( array( 'isOpen' ) );

		$writer->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( false ) );

		\FileSystem::setFOpenResult( false );

		@$writer->open(); // suppress 'call to stream_open() failed' warning

		self::fail( "Unexpected: Stream should not be able to open." );
	}

	public function testClose() {
		$writer = $this->getInstance( array( 'isClosed' ) );

		$writer->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( false ) );

		$writer->open();
		$writer->close();
	}

	public function testCloseAlreadyClosed() {
		$writer = $this->getInstance( array( 'isClosed' ) );
		$writer->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$writer->close();
	}

	public function testIsReadable() {
		$file = $this->getFile();
		$writer = new FileStreamWriter( $file );

		self::assertFalse( $writer->isReadable() );
	}

	public function testIsWritable() {
		$file = $this->getFile();
		$writer = new FileStreamWriter( $file );

		IsWritable::$result = true;

		self::assertTrue( $writer->isWritable() );
	}
}
