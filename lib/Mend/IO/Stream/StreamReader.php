<?php
namespace Mend\IO\Stream;

abstract class StreamReader implements Stream {
	/**
	 * Reads from the stream.
	 *
	 * @return string
	 */
	abstract public function read();

	/**
	 * @see Stream::isWritable()
	 */
	final public function isWritable() {
		return false;
	}
}