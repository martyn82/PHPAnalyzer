<?php
namespace Mend\Logging;

class LoggerTest extends \TestCase {
	public function testWrite() {
		$stream = $this->createStreamWriter();
		$stream->expects( self::once() )->method( 'open' );
		$stream->expects( self::once() )->method( 'write' );

		Logger::setWriter( $stream );
		Logger::info( 'message' );
	}

	private function createStreamWriter() {
		$isOpen = false;

		$stream = $this->getMock(
			'\Mend\IO\Stream\StreamWriter',
			array( 'open', 'close', 'write', 'isOpen', 'isClosed', 'isWritable' )
		);

		$stream->expects( self::any() )->method( 'isOpen' )->will(
			self::returnCallback(
				function () use ( & $isOpen ) {
					return $isOpen;
				}
			)
		);
		$stream->expects( self::any() )->method( 'isClosed' )->will(
			self::returnCallback(
				function () use ( & $isOpen ) {
					return !$isOpen;
				}
			)
		);

		return $stream;
	}
}