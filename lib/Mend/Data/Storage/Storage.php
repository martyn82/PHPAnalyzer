<?php
namespace Mend\Data\Storage;

use Mend\Collections\Map;
use Mend\Data\DataPage;
use Mend\Data\SortOptions;

abstract class Storage {
	/**
	 * Retrieves a result set of given entity that satisfy the criteria.
	 *
	 * @param string $entity
	 * @param Map $criteria
	 * @param SortOptions $sortOptions
	 * @param DataPage $dataPage
	 *
	 * @return ResultSet
	 */
	abstract public function select( $entity, Map $criteria, SortOptions $sortOptions, DataPage $dataPage );

	/**
	 * Inserts given records.
	 *
	 * @param string $entity
	 * @param RecordSet $records
	 *
	 * @return ResultSet
	 */
	abstract public function insert( $entity, RecordSet $records );

	/**
	 * Updates given records.
	 *
	 * @param string $entity
	 * @param RecordSet $records
	 *
	 * @return ResultSet
	 */
	abstract public function update( $entity, RecordSet $records );

	/**
	 * Deletes given records.
	 *
	 * @param string $entity
	 * @param RecordSet $records
	 *
	 * @return ResultSet
	 */
	abstract public function delete( $entity, RecordSet $records );
}
