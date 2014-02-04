<?php
namespace Mend\Metrics\Volume;

use Mend\Metrics\Report\Report;
use Mend\Metrics\Report\Partition\CodePartition;

class VolumeReport extends Report {
	/**
	 * Constructs a new volume report.
	 */
	public function __construct() {
		parent::__construct();

		$this->totalLines( CodePartition::createEmpty() );
		$this->totalLinesOfCode( CodePartition::createEmpty() );
		$this->totalLinesOfComments( CodePartition::createEmpty() );
		$this->totalBlankLines( CodePartition::createEmpty() );
	}

	/**
	 * Gets/sets total lines partition.
	 *
	 * @param CodePartition $partition
	 *
	 * @return CodePartition
	 */
	public function totalLines( CodePartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( VolumeType::VOLUME_LINES, $partition );
		}

		return $this->getPartition( VolumeType::VOLUME_LINES );
	}

	/**
	 * Gets/sets total lines of code partition.
	 *
	 * @param CodePartition $partition
	 *
	 * @return CodePartition
	 */
	public function totalLinesOfCode( CodePartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( VolumeType::VOLUME_LINES_OF_CODE, $partition );
		}

		return $this->getPartition( VolumeType::VOLUME_LINES_OF_CODE );
	}

	/**
	 * Gets/sets total lines of comments partition.
	 *
	 * @param CodePartition $partition
	 *
	 * @return CodePartition
	 */
	public function totalLinesOfComments( CodePartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( VolumeType::VOLUME_LINES_OF_COMMENTS, $partition );
		}

		return $this->getPartition( VolumeType::VOLUME_LINES_OF_COMMENTS );
	}

	/**
	 * Gets/sets total blank lines partition.
	 *
	 * @param CodePartition $partition
	 *
	 * @return CodePartition
	 */
	public function totalBlankLines( CodePartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( VolumeType::VOLUME_LINES_BLANK, $partition );
		}

		return $this->getPartition( VolumeType::VOLUME_LINES_BLANK );
	}

	/**
	 * @see Report::toArray()
	 */
	public function toArray() {
		$result = array();

		foreach ( $this->getPartitions()->toArray() as $name => $partition ) {
			/* @var $partition CodePartition */
			$result[ $name ] = $partition->toArray();
		}

		return $result;
	}
}
