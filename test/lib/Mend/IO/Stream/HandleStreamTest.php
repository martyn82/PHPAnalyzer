<?php
namespace Mend\IO\Stream;

abstract class HandleStreamTest extends \TestCase {
	private $handle;

	protected function createHandle( $mode ) {
		$fileName = \FileSystem::SCHEME . ':///foo';
		$this->handle = fopen( $fileName, $mode );
		return $this->handle;
	}

	public function tearDown() {
		if ( is_resource( $this->handle ) ) {
			fclose( $this->handle );
		}

		$this->handle = null;
	}

	abstract public function testConstructor();

	/**
	 * @expectedException \InvalidArgumentException
	 */
	abstract public function testConstructorInvalidHandle();
	abstract public function testOperation();

	/**
	 * @expectedException \Mend\IO\Stream\StreamClosedException
	 */
	abstract public function testOperationOnClosedStream();

	abstract public function testOpen();
	abstract public function testClose();
	abstract public function testIsWritable();
	abstract public function testIsReadable();
}