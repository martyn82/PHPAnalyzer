<?php
namespace Mend\Logging;

class ConsoleLogWriter extends LogWriter {
	/**
	 * @var resource
	 */
	private $handle;

	/**
	 * Constructs a new log writer instance.
	 *
	 * @param resource $handle
	 *
	 * @throws LogException
	 */
	public function __construct( $handle ) {
		if ( !is_resource( $handle ) ) {
			throw new LogException( "The given handle is not a valid log writer handle." );
		}

		$this->handle = $handle;
	}

	/**
	 * Destructs the log writer and clears resources.
	 */
	public function __destruct() {
		$this->handle = null;
	}

	/**
	 * Writes a message to log.
	 *
	 * @param string $message
	 */
	public function write( $message ) {
		fwrite( $this->handle, $message );
	}
}