<?php
namespace Repository;

use Mend\Data\Page;
use Mend\Data\Repository;
use Mend\Data\SortOptions;
use Mend\IO\FileSystem\Directory;

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
		return array();
	}

	/**
	 * @see Repository::get()
	 */
	public function get( $id ) {
		return null;
	}
}
