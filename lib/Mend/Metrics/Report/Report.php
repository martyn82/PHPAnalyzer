<?php
namespace Mend\Metrics\Report;

use Mend\Collections\Map;
use Mend\Metrics\Report\Partition\CodePartition;

abstract class Report {
	/**
	 * @var Map
	 */
	private $partitions;

	/**
	 * Constructs a new report.
	 */
	public function __construct() {
		$this->partitions = new Map();
	}

	/**
	 * Adds the named code partition.
	 *
	 * @param string $name
	 * @param CodePartition $partition
	 */
	protected function addPartition( $name, CodePartition $partition ) {
		$this->partitions->set( $name, $partition );
	}

	/**
	 * Retrieves the named code partition.
	 *
	 * @param string $name
	 *
	 * @return CodePartition
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function getPartition( $name ) {
		if ( !$this->partitions->hasKey( $name ) ) {
			throw new \InvalidArgumentException( "No such partition: '{$name}'." );
		}

		return $this->partitions->get( $name );
	}

	/**
	 * Retrieves all partitions.
	 *
	 * @return Map
	 */
	protected function getPartitions() {
		return $this->partitions;
	}

	/**
	 * Converts this object to an array representation.
	 *
	 * @return array
	 */
	abstract public function toArray();
}
