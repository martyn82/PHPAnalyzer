<?php
namespace Mend\IO\Stream;

use Mend\IO\FileSystem\File;
class FileStreamWriterTest extends \TestCase {
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

	public function testFileWriter() {
		$fileName = tempnam( '/tmp', 'test' );

		$writer = new FileStreamWriter( new File( $fileName ) );

		self::assertTrue( $writer->isClosed() );
		self::assertFalse( $writer->isOpen() );

		try {
			$writer->write( 'Foo' );
			self::fail( "Write to a closed stream succeeded." );
		}
		catch ( StreamClosedException $e ) {
			self::assertNotNull( $e );
		}

		$writer->open();
		self::assertTrue( $writer->isOpen() );
		self::assertFalse( $writer->isClosed() );

		$writer->close();
		self::assertTrue( $writer->isClosed() );
		self::assertFalse( $writer->isOpen() );

		unlink( $fileName );
	}
}
