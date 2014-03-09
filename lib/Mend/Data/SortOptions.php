<?php
namespace Mend\Data;

class SortOptions {
	/**
	 * @var array
	 */
	private $sortOptions;

	/**
	 * Constructs a new SortOptions instance.
	 */
	public function __construct() {
		$this->sortOptions = array();
	}

	/**
	 * Adds a sort field.
	 *
	 * @param string $fieldName
	 * @param string $direction
	 * @param boolean $prepend
	 */
	public function addSortField( $fieldName, $direction, $prepend = false ) {
		$sortOption = array( $fieldName => $direction );

		if ( $prepend ) {
			array_unshift( $this->sortOptions, $sortOption );
			return;
		}

		array_push( $this->sortOptions, $sortOption );
	}

	/**
	 * Retrieves the sort options as a multi-dimensional array.
	 * Each entry in the array is a key-value pair of field name and sort direction.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->sortOptions;
	}
}
