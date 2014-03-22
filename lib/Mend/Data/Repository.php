<?php
namespace Mend\Data;

use Mend\Collections\Map;

interface Repository {
	/**
	 * Retrieves all objects matching given criteria.
	 *
	 * @param Map $criteria
	 * @param SortOptions $sortOptions
	 * @param DataPage $page
	 *
	 * @return DataObjectCollection
	 */
	function matching( Map $criteria, SortOptions $sortOptions, DataPage $page );

	/**
	 * Retrieves all objects.
	 *
	 * @param SortOptions $sortOptions
	 * @param DataPage $page
	 *
	 * @return DataObjectCollection
	*/
	function all( SortOptions $sortOptions, DataPage $page );

	/**
	 * Retrieves the object with given identity.
	 *
	 * @param string $identity
	 *
	 * @return DataObject
	*/
	function get( $identity );
}
