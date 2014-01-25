<?php
namespace Mend\Metrics\Report\UnitSize;

use Mend\Metrics\Analyze\UnitSize\UnitSizeCategory;
use Mend\Metrics\Report\Report;
use Mend\Metrics\Report\Partition\MethodPartition;

class UnitSizeReport extends Report {
	/**
	 * Constructs a new unit size report.
	 */
	public function __construct() {
		parent::__construct();

		$this->small( MethodPartition::createEmpty() );
		$this->medium( MethodPartition::createEmpty() );
		$this->large( MethodPartition::createEmpty() );
		$this->veryLarge( MethodPartition::createEmpty() );
	}

	/**
	 * Gets/sets small partition.
	 *
	 * @param MethodPartition $partition
	 *
	 * @return MethodPartition
	 */
	public function small( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( UnitSizeCategory::SIZE_SMALL, $partition );
		}

		return $this->getPartition( UnitSizeCategory::SIZE_SMALL );
	}

	/**
	 * Gets/sets medium partition.
	 *
	 * @param MethodPartition $partition
	 *
	 * @return MethodPartition
	 */
	public function medium( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( UnitSizeCategory::SIZE_MEDIUM, $partition );
		}

		return $this->getPartition( UnitSizeCategory::SIZE_MEDIUM );
	}

	/**
	 * Gets/sets large partition.
	 *
	 * @param CodePartition $partition
	 *
	 * @return CodePartition
	 */
	public function large( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( UnitSizeCategory::SIZE_LARGE, $partition );
		}

		return $this->getPartition( UnitSizeCategory::SIZE_LARGE );
	}

	/**
	 * Gets/sets very large partition.
	 *
	 * @param MethodPartition $partition
	 *
	 * @return MethodPartition
	 */
	public function veryLarge( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( UnitSizeCategory::SIZE_VERY_LARGE, $partition );
		}

		return $this->getPartition( UnitSizeCategory::SIZE_VERY_LARGE );
	}
}