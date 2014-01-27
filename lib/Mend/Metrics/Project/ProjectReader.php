<?php
namespace Mend\Metrics\Project;

use Mend\IO\DirectoryStream;
use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;

class ProjectReader {
	/**
	 * @var Project
	 */
	private $project;

	/**
	 * @var FileArray
	 */
	private $files;

	/**
	 * Constructs a new Project Reader.
	 *
	 * @param Project $project
	 */
	public function __construct( Project $project ) {
		$this->project = $project;
	}

	/**
	 * Retrieves the files from the project.
	 *
	 * @return FileArray
	 */
	public function getFiles() {
		if ( is_null( $this->files ) ) {
			$this->files = $this->getFilesFromDirectory( $this->getProjectRoot() );
		}

		return $this->files;
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
	 *
	 * @return FileArray
	 */
	private function getFilesFromDirectory( Directory $directory ) {
		$stream = new DirectoryStream( $directory );
		return new FileArray( $this->getFilesFromStream( $stream->getIterator() ) );
	}

	/**
	 * Retrieves the files from given iterator.
	 *
	 * @param \DirectoryIterator $stream
	 *
	 * @return array
	 */
	private function getFilesFromStream( \DirectoryIterator $stream ) {
		$files = array();

		foreach ( $stream as $iterator ) {
			/* @var $iterator \DirectoryIterator */
			if ( $iterator->isDot() ) {
				continue;
			}

			if ( $iterator->isFile() ) {
				$files[] = new File( $iterator->getPath() . DIRECTORY_SEPARATOR . $iterator->getFilename() );
			}
			else if ( $iterator->isDir() ) {
				$files = array_merge(
					$files,
					$this->getFilesFromStream(
							new \DirectoryIterator( $iterator->getPath() . DIRECTORY_SEPARATOR . $iterator->getFilename() )
					)
				);
			}
		}

		return $files;
	}
}