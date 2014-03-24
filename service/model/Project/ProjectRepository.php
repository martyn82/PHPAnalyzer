<?php
namespace Model\Project;

use Mend\Collections\Map;

use Mend\Data\DataMapper;
use Mend\Data\DataPage;
use Mend\Data\Repository;
use Mend\Data\SortOptions;
use Mend\IO\FileSystem\Directory;
use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Report\ReportType;

class ProjectRepository implements Repository {
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
	public function matching( Map $criteria, SortOptions $sortOptions, DataPage $page ) {
		return array();
	}

	/**
	 * @see Repository::all()
	 */
	public function all( SortOptions $sortOptions, DataPage $page ) {
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
	public function get( $identity ) {
		return $this->mapper->select();

		$reports = $this->loadData( $identity );

		if ( empty( $reports[ $identity ] ) ) {
			throw new \Exception( "Project with id '{$identity}' not found." );
		}

		$report = reset( $reports[ $identity ] );
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
			$reports[ $identity ]
		);

		return $project;
	}
}
