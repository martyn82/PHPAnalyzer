<?php
namespace Mend\IO\Stream;

class NullStreamWriterTest extends \TestCase {
	public function testConstructor() {
		$writer = new NullStreamWriter();
		self::assertNotNull( $writer );
	}

	public function testWrite() {
		$writer = new NullStreamWriter();
		$writer->write( 'foo' );

		self::assertTrue( true, "NullStreamWriter::write() does not do anything." );
	}

	public function testOpen() {
		$writer = new NullStreamWriter();

		self::assertTrue( $writer->isClosed() );
		self::assertFalse( $writer->isOpen() );

		$writer->open();

		self::assertFalse( $writer->isClosed() );
		self::assertTrue( $writer->isOpen() );
	}

	public function testClose() {
		$writer = new NullStreamWriter();

		self::assertTrue( $writer->isClosed() );
		self::assertFalse( $writer->isOpen() );

		$writer->open();

		self::assertFalse( $writer->isClosed() );
		self::assertTrue( $writer->isOpen() );

		$writer->close();

		self::assertTrue( $writer->isClosed() );
		self::assertFalse( $writer->isOpen() );
	}

	public function testIsWritable() {
		$writer = new NullStreamWriter();
		self::assertTrue( $writer->isWritable() );
	}

	public function testIsReadable() {
		$writer = new NullStreamWriter();
		self::assertFalse( $writer->isReadable() );
	}
}