<?php
namespace Mend\IO\FileSystem;

use Mend\IO\FileVisitor;

interface FileSystem {
	/**
	 * Retrieves the fully qualified name of this object.
	 *
	 * @return string
	 */
	function getName();

	/**
	 * Determines whether the file system object exists.
	 *
	 * @return boolean
	 */
	function exists();

	/**
	 * Determines whether the file system object is a directory.
	 *
	 * @return boolean
	 */
	function isDirectory();

	/**
	 * Determines whether the file system object is a file.
	 *
	 * @return boolean
	 */
	function isFile();

	/**
	 * Converts this instance to its string representation.
	 *
	 * @return string
	 */
	function __toString();
}