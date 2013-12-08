<?php
namespace FileSystem;

use \Logging\Logger;

class Directory {
	/**
	 * @var string
	 */
	private $location;

	/**
	 * Constructs a new directory instance.
	 *
	 * @param string $location
	 *
	 * @throws \Exception
	 */
	public function __construct( $location ) {
		if ( !is_dir( $location ) ) {
			throw new \Exception( "Given location is not a directory." );
		}

		$this->location = $location;
	}

	/**
	 * Retrieves the full path name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->location;
	}

	/**
	 * Retrieves the files in the directory.
	 *
	 * @param string $pattern
	 *
	 * @return \FileSystem\FileArray
	 */
	public function getFiles( $pattern = null ) {
		Logger::info( "Retrieve files in directory {$this->getName()} by pattern: {$pattern}." );

		$oldWorkingDir = getcwd();
		chdir( $this->location );

		$files = array();

		if ( is_null( $pattern ) ) {
			$files = $this->getFileEntries();
		}
		else {
			$files = $this->getFilesByPattern( $pattern );
		}

		chdir( $oldWorkingDir );

		return new FileArray( $files );
	}

	/**
	 * Retrieves all file entries.
	 *
	 * @return array
	 */
	private function getFileEntries() {
		Logger::info( "Retrieve files in directory {$this->getName()}." );

		$self = $this;
		return array_map(
			function ( $entry ) use ( $self ) {
				return new File( $self->location . DIRECTORY_SEPARATOR . $entry );
			},
			array_filter(
				scandir( $this->location ),
				function ( $entry ) {
					return is_file( $entry );
				}
			)
		);
	}

	/**
	 * Retrieves all directory entries.
	 *
	 * @return array
	 */
	private function getDirectoryEntries() {
		Logger::info( "Retrieve directories in directory {$this->getName()}." );

		return array_filter(
			scandir( $this->location ),
			function ( $entry ) {
				return is_dir( $entry );
			}
		);
	}

	/**
	 * Retrieves all files by pattern.
	 *
	 * @param string $pattern
	 *
	 * @return array
	 */
	private function getFilesByPattern( $pattern ) {
		Logger::info( "Retrieve files in directory {$this->getName()} by pattern: {$pattern}." );

		$self = $this;
		return array_map(
			function ( $entry ) use ( $self ) {
				return new File( $self->location . DIRECTORY_SEPARATOR . $entry );
			},
			glob( $pattern )
		);
	}
}