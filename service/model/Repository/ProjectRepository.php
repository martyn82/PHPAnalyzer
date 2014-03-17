<?php
namespace Repository;

use Mend\Data\DataMapper;
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

class ProjectRepository extends Repository {
	/**
	 * @var DataMapper
	 */
	private $mapper;

	/**
	 * Constructs a new ProjectRepository instance.
	 *
	 * @param DataMapper $mapper
	 */
	public function __construct( DataMapper $mapper ) {
		$this->mapper = $mapper;
	}

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
		return $this->mapper->select();

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
}
