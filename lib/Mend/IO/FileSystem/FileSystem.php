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
	 * Converts this instance to its string representation.
	 *
	 * @return string
	 */
	function __toString();
}