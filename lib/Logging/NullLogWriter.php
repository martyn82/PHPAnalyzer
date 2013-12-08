<?php
namespace Logging;

class NullLogWriter extends LogWriter {
	/**
	 * Writes a message to log.
	 *
	 * @param string $message
	 */
	public function write( $message ) {
		// vanish
	}
}