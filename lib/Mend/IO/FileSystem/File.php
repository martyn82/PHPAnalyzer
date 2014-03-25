<?php
namespace Mend\IO\FileSystem;

use Mend\IO\FileVisitor;

class File implements FileSystem {
	/**
	 * @var string
	 */
	private $location;

	/**
	 * Constructs a new File instance.
	 *
	 * @param string $location
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct( $location ) {
		if ( empty( $location ) ) {
			throw new \InvalidArgumentException( "Location cannot be empty." );
		}

		$this->location = $location;
	}

	/**
	 * @see FileSystem::getName()
	 */
	public function getName() {
		return $this->location;
	}

	/**
	 * Retrieves the file extension.
	 *
	 * @return string
	 */
	public function getExtension() {
		$parts = \pathinfo( $this->location );

		if ( !empty( $parts[ 'extension' ] ) ) {
			return $parts[ 'extension' ];
		}

		return '';
	}

	/**
	 * @see FileSystem::exists()
	 */
	public function exists() {
		return \file_exists( $this->location );
	}

	/**
	 * @see FileSystem::isDirectory()
	 */
	public function isDirectory() {
		return \is_dir( $this->location );
	}

	/**
	 * @see FileSystem::isFile()
	 */
	public function isFile() {
		return \is_file( $this->location );
	}

	/**
	 * @see FileSystem::delete()
	 */
	public function delete() {
		return \unlink( $this->location );
	}

	/**
	 * Returns true if the file is writable.
	 *
	 * @return boolean
	 */
	public function canWrite() {
		return \is_writable( $this->location );
	}

	/**
	 * Returns true if the file is readable.
	 *
	 * @return boolean
	 */
	public function canRead() {
		return \is_readable( $this->location );
	}

	/**
	 * @see FileSystem::__toString()
	 */
	public function __toString() {
		return $this->location;
	}
}