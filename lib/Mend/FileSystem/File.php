<?php
namespace Mend\FileSystem;

use \Mend\Logging\Logger;

class File {
	/**
	 * @var string
	 */
	private $location;

	/**
	 * @var string
	 */
	private $contents;

	/**
	 * @param string $location
	 *
	 * @throws \Exception
	 */
	public function __construct( $location ) {
		if ( !is_file( $location ) ) {
			throw new \Exception( "Given location is not a valid file." );
		}

		$this->location = $location;
	}

	/**
	 * Retrieves the file's full path name.
	 *
	 * @return string
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
		$parts = explode( ".", $this->location );
		return end( $parts );
	}

	/**
	 * Retrieves the file's contents.
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function getContents() {
		if ( !is_null( $this->contents ) ) {
			Logger::info( "Using cached contents of file <{$this->getName()}>." );
			return $this->contents;
		}

		if ( !is_readable( $this->location ) ) {
			throw new \Exception( "File is not readable." );
		}

		Logger::info( "Reading file contents of file <{$this->getName()}>." );

		$this->contents = file_get_contents( $this->location );
		return $this->contents;
	}
}