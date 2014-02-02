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
		$parts = pathinfo( $this->location );
		return $parts[ 'extension' ];
	}

	/**
	 * @see FileSystem::__toString()
	 */
	public function __toString() {
		return $this->location;
	}
}