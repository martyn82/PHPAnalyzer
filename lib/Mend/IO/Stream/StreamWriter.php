<?php
namespace Mend\IO\Stream;

abstract class StreamWriter implements Stream {
	/**
	 * Writes a string to stream.
	 *
	 * @param string $value
	 */
	abstract public function write( $value );

	/**
	 * @see Stream::isReadable()
	 */
	final public function isReadable() {
		return false;
	}
}