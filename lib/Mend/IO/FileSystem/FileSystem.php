<?php
namespace Mend\IO\FileSystem;

use Mend\IO\FileVisitor;

interface FileSystem {
	const DIRECTORY_SEPARATOR = DIRECTORY_SEPARATOR;

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
	 * Deletes this file system object.
	 */
	function delete();

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