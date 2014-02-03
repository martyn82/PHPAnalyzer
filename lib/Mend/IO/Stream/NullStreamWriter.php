<?php
namespace Mend\IO\Stream;

class NullStreamWriter extends StreamWriter {
	/**
	 * @var boolean
	 */
	private $isOpen;

	/**
	 * Constructs a new NullStreamWriter.
	 */
	public function __construct() {
		$this->isOpen = false;
	}

	/**
	 * @see StreamWriter::write()
	 */
	public function write( $value ) {
		// vanish
	}

	/**
	 * @see Stream::open()
	 */
	public function open() {
		$this->isOpen = true;
	}

	/**
	 * @see Stream::close()
	 */
	public function close() {
		$this->isOpen = false;
	}

	/**
	 * @see Stream::isOpen()
	 */
	public function isOpen() {
		return $this->isOpen;
	}

	/**
	 * @see Stream::isClosed()
	 */
	public function isClosed() {
		return !$this->isOpen;
	}

	/**
	 * @see Stream::isWritable()
	 */
	public function isWritable() {
		return true;
	}
}