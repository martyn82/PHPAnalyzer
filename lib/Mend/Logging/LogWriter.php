<?php
namespace Mend\Logging;

abstract class LogWriter {
	/**
	 * Write a message to log.
	 *
	 * @param string $message
	 */
	abstract public function write( $message );
}