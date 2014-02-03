<?php
namespace Mend\IO\Stream;

class HandleStreamWriter extends StreamWriter {
	/**
	 * @var resource
	 */
	private $handle;

	/**
	 * Constructs a new handle stream writer.
	 *
	 * @param resource $handle
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct( $handle ) {
		if ( !is_resource( $handle ) ) {
			throw new \InvalidArgumentException( "Given handle is not a valid resource." );
		}

		$this->handle = $handle;
	}

	/**
	 * @see StreamWriter::write()
	 *
	 * @throws StreamClosedException
	 */
	public function write( $value ) {
		if ( $this->isClosed() ) {
			throw new StreamClosedException( "The stream should be open to write." );
		}

		fwrite( $this->handle, $value );
	}

	/**
	 * @see Stream::open()
	 */
	public function open() {
		return;
	}

	/**
	 * @see Stream::close()
	 */
	public function close() {
		return;
	}

	/**
	 * @see Stream::isOpen()
	 */
	public function isOpen() {
		return is_resource( $this->handle );
	}

	/**
	 * @see Stream::isClosed()
	 */
	public function isClosed() {
		return is_null( $this->handle );
	}

	/**
	 * @see Stream::isWritable()
	 */
	public function isWritable() {
		return true;
	}
}