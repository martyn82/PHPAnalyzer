<?php
namespace Mend\IO\Stream;

use Mend\IO\FileSystem\File;
use Mend\IO\IOException;

class FileStreamWriter extends StreamWriter {
	/**
	 * @var File
	 */
	private $file;

	/**
	 * @var resource
	 */
	private $handle;

	/**
	 * Constructs a new FileStreamWriter.
	 *
	 * @param File $file
	 */
	public function __construct( File $file ) {
		$this->file = $file;
	}

	/**
	 * Cleans up before object destruction.
	 */
	public function __destruct() {
		if ( $this->isOpen() ) {
			$this->close();
		}
	}

	/**
	 * @see StreamWriter::write()
	 *
	 * @throws StreamNotWritableException
	 * @throws StreamClosedException
	 */
	public function write( $value ) {
		if ( !$this->isWritable() ) {
			throw new StreamNotWritableException( "The stream is not writable." );
		}

		if ( $this->isClosed() ) {
			throw new StreamClosedException( "The stream must be open to write." );
		}

		fwrite( $this->handle, $value );
	}

	/**
	 * @see Stream::open()
	 *
	 * @throws IOException
	 */
	public function open() {
		if ( $this->isOpen() ) {
			return;
		}

		$status = fopen( $this->file->getName(), 'w' );

		if ( $status === false ) {
			throw new IOException( "Unable to open the file: {$this->file->getName()}." );
		}

		$this->handle = $status;
	}

	/**
	 * @see Stream::close()
	 */
	public function close() {
		if ( $this->isClosed() ) {
			return;
		}

		fclose( $this->handle );
		$this->handle = null;
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
		return !is_resource( $this->handle );
	}

	/**
	 * @see Stream::isWritable()
	 */
	public function isWritable() {
		return $this->file->canWrite();
	}
}
