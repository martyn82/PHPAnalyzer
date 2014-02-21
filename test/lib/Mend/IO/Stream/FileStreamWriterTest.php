<?php
namespace Mend\IO\Stream;

require_once "FileStreamTest.php";

use Mend\IO\FileSystem\File;

class FileStreamWriterTest extends FileStreamTest {
	public function testStreamInterface() {
		$streamWriter = $this->getMock(
			'\Mend\IO\Stream\StreamWriter',
			array( 'open', 'close', 'write', 'isOpen', 'isClosed', 'isReadable', 'isWritable' ),
			array()
		);
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

	/**
	 * @param array $methods
	 * @param File $file
	 *
	 * @return FileStreamWriter
	 */
	private function getWriter( array $methods = array(), File $file = null ) {
		if ( is_null( $file ) ) {
			$file = $this->getMock( '\Mend\IO\FileSystem\File', array(), array(), '', false );
		}

		return $this->getMock(
			'\Mend\IO\Stream\FileStreamWriter',
			$methods,
			array( $file )
		);
	}

	public function testConstruct() {
		$file = new File( '/tmp/foo' );
		$writer = new FileStreamWriter( $file );

		self::assertNotNull( $writer );
	}

	public function testWriterNeverReadable() {
		$writer = $this->getWriter();
		self::assertFalse( $writer->isReadable() );
	}

	public function testWrite() {
		$writer = $this->getWriter( array( 'isClosed' ) );

		$writer->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( false ) );

		self::$isWritableResult = true;
		self::$isResourceResult = true;

		$writer->write( 'foo' );
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamNotWritableException
	 */
	public function testReadFileNonExistent() {
		$writer = $this->getWriter( array( 'isWritable' ) );

		$writer->expects( self::any() )
			->method( 'isWritable' )
			->will( self::returnValue( false ) );

		$writer->write( 'foo' );

		self::fail( "Write to a non-existent file." );
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamClosedException
	 */
	public function testReadClosedStream() {
		$writer = $this->getWriter( array( 'isClosed', 'isWritable' ) );

		$writer->expects( self::any() )
			->method( 'isWritable' )
			->will( self::returnValue( true ) );

		$writer->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$writer->write( 'foo' );
	}

	public function testOpen() {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array(), array(), '', false );
		$writer = new FileStreamWriter( $file );

		self::$isResourceResult = false;
		self::$fopenResult = true;

		self::assertFalse( $writer->isOpen() );

		$writer->open();
		self::$isResourceResult = true;

		self::assertTrue( $writer->isOpen() );
	}

	public function testOpenAlreadyOpen() {
		$writer = $this->getWriter( array( 'isOpen' ) );

		$writer->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( true ) );

		$writer->open();
	}

	/**
	 * @expectedException \Mend\IO\IOException
	 */
	public function testOpenFailed() {
		$writer = $this->getWriter( array( 'isOpen' ) );
		$writer->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( false ) );

		self::$fopenResult = false;

		$writer->open();

		self::fail( "Unexpected: Stream should not be able to open." );
	}

	public function testClose() {
		$writer = $this->getWriter( array( 'isClosed' ) );
		$writer->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( false ) );

		$writer->close();
	}

	public function testCloseAlreadyClosed() {
		$writer = $this->getWriter( array( 'isClosed' ) );
		$writer->expects( self::any() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$writer->close();
	}
}
