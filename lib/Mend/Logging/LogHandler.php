<?php
namespace Mend\Logging;

abstract class LogHandler {
	/**
	 * Logs the given message.
	 *
	 * @param string $message
	 */
	abstract public function log( $message );
}
