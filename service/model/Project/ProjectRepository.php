<?php
namespace Model\Project;

use Mend\Collections\Map;
use Mend\Data\DataMapper;
use Mend\Data\DataObjectCollection;
use Mend\Data\DataPage;
use Mend\Data\Repository;
use Mend\Data\SortOptions;

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
	public function matching( Map $criteria, SortOptions $sortOptions, DataPage $dataPage ) {
		return $this->mapper->select( $criteria, $sortOptions, $dataPage );
	}

	/**
	 * @see Repository::all()
	 */
	public function all( SortOptions $sortOptions, DataPage $page ) {
		$collection = $this->mapper->select( new Map(), $sortOptions, $page );
		$projects = $collection->toArray();

		$unique = array_reduce(
			$projects,
			function ( array $result, Project $project ) {
				if ( !isset( $result[ $project->getKey() ] ) ) {
					$result[ $project->getKey() ] = $project;
				}

				return $result;
			},
			array()
		);

		$result = new DataObjectCollection();

		foreach ( $unique as $project ) {
			$result->add( $project );
		}

		return $result;
	}

	/**
	 * @see Repository::get()
	 */
	public function get( $identity ) {
		$criteria = new Map( array( 'id' => $identity ) );
		$collection = $this->mapper->select( $criteria, new SortOptions(), new DataPage() );

		if ( $collection->isEmpty() ) {
			throw new \UnexpectedValueException( "Object not found: project with identity '{$identity}'" );
		}

		$array = $collection->toArray();
		return $array[ 0 ];
	}
}
