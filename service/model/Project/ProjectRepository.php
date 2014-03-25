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
	public function matching( Map $criteria, SortOptions $sortOptions, DataPage $page ) {
		return array();
	}

	/**
	 * @see Repository::all()
	 */
	public function all( SortOptions $sortOptions, DataPage $page ) {
		return new DataObjectCollection();
	}

	/**
	 * @see Repository::get()
	 */
	public function get( $identity ) {
		$criteria = new Map( array( 'id' => $identity ) );
		return $this->mapper->select( $criteria, new SortOptions(), new DataPage() );
	}
}
