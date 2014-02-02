<?php
namespace Mend\IO\Stream;

interface Stream {
	/**
	 * Opens the stream.
	 */
	function open();

	/**
	 * Closes the stream.
	 */
	function close();

	/**
	 * Determines whether the stream is open.
	 *
	 * @return boolean
	 */
	function isOpen();

	/**
	 * Determines whether the stream is closed.
	 *
	 * @return boolean
	 */
	function isClosed();

	/**
	 * Determines whether the stream is readable.
	 *
	 * @return boolean
	 */
	function isReadable();

	/**
	 * Determines whether the stream is writable.
	 *
	 * @return boolean
	 */
	function isWritable();
}