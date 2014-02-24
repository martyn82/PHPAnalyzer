<?php
namespace Mend\IO\Stream;

use Mend\IO\FileSystem\File;

abstract class FileStreamTest extends \TestCase {
	const FS_PROTOCOL = \FileSystem::PROTOCOL;

	public function setUp() {
		\FileSystem::resetResults();
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	protected function getProtocol() {
		return self::FS_PROTOCOL . '://';
	}

	/**
	 * Retrieves a mocked instance.
	 *
	 * @param array $methods
	 * @param File $file
	 *
	 * @return Stream
	 */
	abstract protected function getInstance( array $methods = array(), File $file = null );

	protected function getFile() {
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

		return $file;
	}

	abstract public function testConstructor();

	abstract public function testFile();

	/**
	 * @expectedException \Mend\IO\Stream\StreamNotReadableException
	 */
	abstract public function testFileNonExistent();

	/**
	 * @expectedException \Mend\IO\Stream\StreamClosedException
	 */
	abstract public function testClosedStream();

	abstract public function testOpen();

	abstract public function testOpenAlreadyOpen();

	/**
	 * @expectedException \Mend\IO\IOException
	 */
	abstract public function testOpenFailed();

	abstract public function testClose();

	abstract public function testCloseAlreadyClosed();

	abstract public function testIsWritable();

	abstract public function testIsReadable();
}