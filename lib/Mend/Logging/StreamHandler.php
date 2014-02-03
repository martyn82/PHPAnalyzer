<?php
namespace Mend\Logging;

use Mend\IO\Stream\StreamWriter;

class StreamHandler extends LogHandler {
	/**
	 * @var StreamWriter
	 */
	private $writer;

	/**
	 * Constructs a new logger.
	 *
	 * @param StreamWriter $writer
	 */
	public function __construct( StreamWriter $writer ) {
		$this->writer = $writer;
	}

	/**
	 * Closes the stream and frees the resources.
	 */
	public function __destruct() {
		if ( $this->writer->isOpen() ) {
			$this->writer->close();
		}

		$this->writer = null;
	}

	/**
	 * @see LogHandler::log()
	 */
	public function log( $message ) {
		$this->writeToStream( $message );
	}

	/**
	 * Writes a message to the stream.
	 *
	 * @param string $message
	 */
	private function writeToStream( $message ) {
		if ( $this->writer->isClosed() ) {
			$this->writer->open();
		}

		$this->writer->write( $message );
	}
}
