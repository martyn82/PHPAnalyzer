<?php
namespace Mend\IO\Stream;

abstract class StreamWriter implements Stream {
	/**
	 * Writes a message to stream.
	 *
	 * @param string $message
	 */
	abstract public function write( $message );

	/**
	 * @see Stream::isReadable()
	 */
	final public function isReadable() {
		return false;
	}
}