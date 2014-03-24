<?php
namespace Mend\Metrics\Project;

use Mend\Collections\Map;
use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\IO\FileSystem\FileSystem;

class ProjectReader {
	/**
	 * @var Project
	 */
	private $project;

	/**
	 * @var Map
	 */
	private $files;

	/**
	 * Constructs a new Project Reader.
	 *
	 * @param Project $project
	 */
	public function __construct( Project $project ) {
		$this->project = $project;
		$this->files = new Map();
	}

	/**
	 * Retrieves the files from the project.
	 *
	 * @param array $extensions
	 *
	 * @return FileArray
	 */
	public function getFiles( array $extensions = null ) {
		$key = $this->getHash( $extensions );

		if ( !$this->files->hasKey( $key ) ) {
			$this->files->set( $key, $this->getFilesFromDirectory( $this->getProjectRoot(), $extensions ) );
		}

		return $this->files->get( $key );
	}

	/**
	 * Creates a hash for given array.
	 *
	 * @param array $values
	 *
	 * @return string
	 */
	private function getHash( array $values = null ) {
		return implode( '|', (array) $values );
	}

	/**
	 * Retrieves the Project's root directory.
	 *
	 * @return Directory
	 */
	private function getProjectRoot() {
		return $this->project->getRoot();
	}

	/**
	 * Retrieves the files from given directory.
	 *
	 * @param Directory $directory
	 * @param array $extensions
	 *
	 * @return FileArray
	 */
	private function getFilesFromDirectory( Directory $directory, array $extensions = null ) {
		$files = $this->getFilesFromStream( $directory->iterator(), $extensions );
		return new FileArray( $files );
	}

	/**
	 * Retrieves the files from given iterator.
	 *
	 * @param \DirectoryIterator $stream
	 * @param array $extensions
	 *
	 * @return array
	 */
	private function getFilesFromStream( \DirectoryIterator $stream, array $extensions = null ) {
		$files = array();
		$extensions = is_array( $extensions ) ? $extensions : array();

		foreach ( $stream as $iterator ) {
			/* @var $iterator \DirectoryIterator */
			if ( $iterator->isDot() ) {
				continue;
			}

			if ( $iterator->isFile() ) {
				if ( !empty( $extensions ) && !in_array( $iterator->getExtension(), $extensions ) ) {
					continue;
				}

				$files[] = new File(
					$iterator->getPath()
					. FileSystem::DIRECTORY_SEPARATOR
					. $iterator->getFilename()
				);
			}
			else if ( $iterator->isDir() ) {
				$files = array_merge(
					$files,
					$this->getFilesFromStream(
						new \DirectoryIterator(
							$iterator->getPath()
							. FileSystem::DIRECTORY_SEPARATOR
							. $iterator->getFilename()
						),
						$extensions
					)
				);
			}
		}

		return $files;
	}
}