<?php
namespace Mend\IO;

use Mend\IO\FileSystem\Directory;

class DirectoryStream {
	/**
	 * @var \DirectoryIterator
	 */
	private $iterator;

	/**
	 * @var Directory
	 */
	private $directory;

	/**
	 * Constructs a new stream.
	 *
	 * @param Directory $directory
	 */
	public function __construct( Directory $directory ) {
		$this->directory = $directory;
	}

	/**
	 * Retrieves the Directory instance.
	 *
	 * @return Directory
	 */
	public function getDirectory() {
		return $this->directory;
	}

	/**
	 * Retrieves the directory name.
	 *
	 * @return string
	 */
	private function getDirectoryName() {
		return $this->directory->getName();
	}

	/**
	 * Retrieves the iterator.
	 *
	 * @return \DirectoryIterator
	 */
	public function getIterator() {
		if ( is_null( $this->iterator ) ) {
			$this->iterator = new \DirectoryIterator( $this->getDirectoryName() );
		}

		return $this->iterator;
	}
}