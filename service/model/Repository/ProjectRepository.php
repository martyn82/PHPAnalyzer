<?php
namespace Repository;

use Mend\Data\Page;
use Mend\Data\Repository;
use Mend\Data\SortOptions;
<<<<<<< HEAD
use Mend\IO\FileSystem\Directory;
=======

use Mend\IO\DirectoryStream;
use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\IO\FileSystem\FileSystem;
use Mend\IO\Stream\FileStreamReader;
>>>>>>> 6db1c58... WIP

use Record\ProjectRecord;

class ProjectRepository implements Repository {
	/**
	 * @see Repository::matching()
	 */
	public function matching( array $criteria, SortOptions $sortOptions, Page $page, & $totalCount = 0 ) {
		return array();
	}

	/**
	 * @see Repository::all()
	 */
	public function all( SortOptions $sortOptions, Page $page, & $totalCount = 0 ) {
<<<<<<< HEAD
		return array();
=======
		$dataDir = new Directory( 'data/' );
		$stream = new DirectoryStream( $dataDir );
		$dirIterator = $stream->getIterator();
		$dataFiles = new FileArray();

		foreach ( $dirIterator as $iterator ) {
			if ( !$iterator->isFile() || $iterator->getExtension() != 'json' ) {
				continue;
			}

			$dataFiles[] = new File(
				$iterator->getPath()
				. FileSystem::DIRECTORY_SEPARATOR
				. $iterator->getFilename()
			);
		}

		$projects = array();

		foreach ( $dataFiles as $file ) {
			/* @var $file File */
			$reader = new FileStreamReader( $file );
			$reader->open();
			$contents = $reader->read();
			$reader->close();

			$report = json_decode( $contents, true );
			$projects[ $report[ 'project' ][ 'key' ] ] = $report[ 'project' ];
		}

		return array_map(
			function ( array $project ) {
				return new ProjectRecord( $project[ 'name' ], $project[ 'key' ], new Directory( $project[ 'path' ] ) );
			},
			array_values( $projects )
		);
>>>>>>> 6db1c58... WIP
	}

	/**
	 * @see Repository::get()
	 */
	public function get( $id ) {
<<<<<<< HEAD
		return null;
=======
		$dataDir = new Directory( 'data/' );
		$stream = new DirectoryStream( $dataDir );
		$dirIterator = $stream->getIterator();
		$dataFiles = new FileArray();

		foreach ( $dirIterator as $iterator ) {
			if ( !$iterator->isFile() || $iterator->getExtension() != 'json' ) {
				continue;
			}

			$dataFiles[] = new File(
				$iterator->getPath()
				. FileSystem::DIRECTORY_SEPARATOR
				. $iterator->getFilename()
			);
		}

		$project = array();

		foreach ( $dataFiles as $file ) {
			/* @var $file File */
			$reader = new FileStreamReader( $file );
			$reader->open();
			$contents = $reader->read();
			$reader->close();

			$report = json_decode( $contents, true );

			if ( $id == $report[ 'project' ][ 'key' ] ) {
				$project = $report[ 'project' ];
				break;
			}
		}

		if ( empty( $project ) ) {
			return null;
		}

		return new ProjectRecord( $project[ 'name' ], $project[ 'key' ], new Directory( $project[ 'path' ] ) );
>>>>>>> 6db1c58... WIP
	}
}
