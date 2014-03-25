<?php
namespace Mend\IO\Stream;

use Mend\IO\FileSystem\File;
use Mend\IO\IOException;

class FileStreamReader extends StreamReader {
	/**
	 * @var File
	 */
	private $file;

	/**
	 * @var resource
	 */
	private $handle;

	/**
	 * Constructs a new FileReader.
	 *
	 * @param File $file
	 */
	public function __construct( File $file ) {
		$this->file = $file;
	}

	/**
	 * Cleans up resources before destruction.
	 */
	public function __destruct() {
		if ( $this->isOpen() ) {
			$this->close();
		}
	}

	/**
	 * @see StreamReader::read()
	 *
	 * @throws StreamNotReadableException
	 * @throws StreamClosedException
	 */
	public function read() {
		if ( !$this->isReadable() ) {
			throw new StreamNotReadableException( "The file is not readable: {$this->file->getName()}" );
		}

		if ( $this->isClosed() ) {
			throw new StreamClosedException( "The stream must be opened to read." );
		}

		return fread( $this->handle, filesize( $this->file->getName() ) );
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

		$status = fopen( $this->file->getName(), 'rb' );

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
	 * @see Stream::isReadable()
	 */
	public function isReadable() {
		return $this->file->canRead();
	}
}