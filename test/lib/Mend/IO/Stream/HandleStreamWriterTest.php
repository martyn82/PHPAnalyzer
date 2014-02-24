<?php
namespace Mend\IO\Stream;

class HandleStreamWriterTest extends HandleStreamTest {
	public function testConstructor() {
		$handle = $this->createHandle( 'w' );

		$writer = new HandleStreamWriter( $handle );
		self::assertNotNull( $writer );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testConstructorInvalidHandle() {
		$handle = 1;

		$writer = new HandleStreamWriter( $handle );
		self::fail( "Test should have triggered an exception." );
	}

	public function testOperation() {
		$handle = $this->createHandle( 'w' );

		$writer = new HandleStreamWriter( $handle );
		self::assertFalse( $writer->isClosed() );

		$writer->write( 'foo' );
	}

	/**
	 * @expectedException \Mend\IO\Stream\StreamClosedException
	 */
	public function testOperationOnClosedStream() {
		$handle = $this->createHandle( 'w' );

		$writer = new HandleStreamWriter( $handle );

		fclose( $handle );

		$writer->write( 'foo' );
	}

	public function testOpen() {
		$handle = $this->createHandle( 'w' );
		$writer = new HandleStreamWriter( $handle );

		self::assertTrue( $writer->isOpen() );
		self::assertFalse( $writer->isClosed() );

		$writer->open(); // HandleStream open() has no effect on the open or closed state

		self::assertTrue( $writer->isOpen() );
		self::assertFalse( $writer->isClosed() );
	}

	public function testClose() {
		$handle = $this->createHandle( 'w' );
		$writer = new HandleStreamWriter( $handle );

		self::assertTrue( $writer->isOpen() );
		self::assertFalse( $writer->isClosed() );

		$writer->close(); // HandleStream close() has no effect on the open or closed state

		self::assertTrue( $writer->isOpen() );
		self::assertFalse( $writer->isClosed() );
	}

	public function testIsWritable() {
		$handle = $this->createHandle( 'w' );
		$writer = new HandleStreamWriter( $handle );

		self::assertTrue( $writer->isWritable() );
	}

	public function testIsReadable() {
		$handle = $this->createHandle( 'w' );
		$writer = new HandleStreamWriter( $handle );

		self::assertFalse( $writer->isReadable() );
	}
}