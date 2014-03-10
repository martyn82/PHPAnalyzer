<?php
namespace Repository;

use Mend\Data\DataPage;
use Mend\Data\Repository;
use Mend\Data\SortOptions;

use Mend\IO\DirectoryStream;
use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\IO\FileSystem\FileSystem;
use Mend\IO\Stream\FileStreamReader;

use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Report\ReportType;

use Record\ProjectRecord;

class ProjectRepository implements Repository {
	/**
	 * @see Repository::matching()
	 */
	public function matching( array $criteria, SortOptions $sortOptions, DataPage $page, & $totalCount = 0 ) {
		return array();
	}

	/**
	 * @see Repository::all()
	 */
	public function all( SortOptions $sortOptions, DataPage $page, & $totalCount = 0 ) {
		$reports = $this->loadData();
		$projects = array();

		foreach ( $reports as $projectKey => $projectReports ) {
			$report = reset( $projectReports );
			$projects[ $projectKey ] = $report[ 'project' ];
		}

		return array_map(
			function ( array $project ) {
				return new ProjectRecord( $project[ 'name' ], $project[ 'key' ], new Directory( $project[ 'path' ] ) );
			},
			array_values( $projects )
		);
	}

	/**
	 * @see Repository::get()
	 *
	 * @throws \Exception
	 */
	public function get( $id ) {
		$reports = $this->loadData( $id );

		if ( empty( $reports[ $id ] ) ) {
			throw new \Exception( "Project with id '{$id}' not found." );
		}

		$report = reset( $reports[ $id ] );
		$projectData = $report[ 'project' ];

		$project = new ProjectRecord(
			$projectData[ 'name' ],
			$projectData[ 'key' ],
			new Directory( $projectData[ 'path' ] )
		);

		$project->reports = array_map(
			function ( array $report ) use ( $project ) {
				return array(
					'report' => $report
				);
			},
			$reports[ $id ]
		);

		return $project;
	}

	/**
	 * Loads project data.
	 *
	 * @param string $id
	 *
	 * @return array
	 */
	protected function loadData( $id = null ) {
		$dataDir = new Directory( 'data/' );
		$stream = new DirectoryStream( $dataDir );
		$dirIterator = $stream->getIterator();
		$dataFiles = new FileArray();

		foreach ( $dirIterator as $iterator ) {
			if ( !$iterator->isFile() || $iterator->getExtension() != 'json' ) {
				continue;
			}

			if ( !is_null( $id ) && substr( $iterator->getFilename(), 0, strlen( $id ) ) != $id ) {
				continue;
			}

			if ( $iterator->getSize() == 0 ) {
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

			if ( !isset( $projects[ $report[ 'project' ][ 'key' ] ] ) ) {
				$projects[ $report[ 'project' ][ 'key' ] ] = array( $report );
			}
			else {
				$projects[ $report[ 'project' ][ 'key' ] ][] = $report;
			}
		}

		return $projects;
	}
}
