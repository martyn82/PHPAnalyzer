<?php
namespace Mend\IO\FileSystem;

use Mend\IO\FileVisitor;

class Directory implements FileSystem {
	/**
	 * @var string
	 */
	private $location;

	/**
	 * Constructs a new Directory instance.
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
	 * Retrieves the basename of the directory.
	 *
	 * @return string
	 */
	public function getBaseName() {
		return \basename( $this->location );
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
	 * @see FileSystem::__toString()
	 */
	public function __toString() {
		return $this->location;
	}
}