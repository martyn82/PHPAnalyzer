<?php
namespace Mend\Data;

interface Repository {
	/**
	 * Retrieves all criteria matching records.
	 *
	 * @param array $criteria
	 * @param SortOptions $sortOptions
	 * @param Page $page
	 * @param integer $totalCount
	 *
	 * @return array
	 */
	function matching( array $criteria, SortOptions $sortOptions, Page $page, & $totalCount = 0 );

	/**
	 * Retrieves all records.
	 *
	 * @param SortOptions $sortOptions
	 * @param Page $page
	 * @param integer $totalCount
	 *
	 * @return array
	*/
	function all( SortOptions $sortOptions, Page $page, & $totalCount = 0 );

	/**
	 * Retrieves the record with given ID.
	 *
	 * @param integer $id
	 *
	 * @return object
	*/
	function get( $id );
}
