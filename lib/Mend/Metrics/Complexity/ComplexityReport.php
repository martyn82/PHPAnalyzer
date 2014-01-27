<?php
namespace Mend\Metrics\Complexity;

use Mend\Metrics\Model\Code\MethodArray;
use Mend\Metrics\Report\Report;
use Mend\Metrics\Report\Partition\CodePartition;
use Mend\Metrics\Report\Partition\MethodPartition;

class ComplexityReport extends Report {
	/**
	 * Constructs a new complexity report.
	 */
	public function __construct() {
		parent::__construct();

		$this->low( MethodPartition::createEmpty() );
		$this->moderate( MethodPartition::createEmpty() );
		$this->high( MethodPartition::createEmpty() );
		$this->veryHigh( MethodPartition::createEmpty() );
	}

	/**
	 * Gets/sets low complexity partition.
	 *
	 * @param MethodPartition $partition
	 *
	 * @return MethodPartition
	 */
	public function low( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( ComplexityRisk::RISK_LOW, $partition );
		}

		return $this->getPartition( ComplexityRisk::RISK_LOW );
	}

	/**
	 * Gets/sets moderate complexity partition.
	 *
	 * @param MethodPartition $partition
	 *
	 * @return MethodPartition
	 */
	public function moderate( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( ComplexityRisk::RISK_MODERATE, $partition );
		}

		return $this->getPartition( ComplexityRisk::RISK_MODERATE );
	}

	/**
	 * Gets/sets high complexity partition.
	 *
	 * @param MethodPartition $partition
	 *
	 * @return MethodPartition
	 */
	public function high( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( ComplexityRisk::RISK_HIGH, $partition );
		}

		return $this->getPartition( ComplexityRisk::RISK_HIGH );
	}

	/**
	 * Gets/sets very high complexity partition.
	 *
	 * @param MethodPartition $partition
	 *
	 * @return MethodPartition
	 */
	public function veryHigh( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( ComplexityRisk::RISK_VERY_HIGH, $partition );
		}

		return $this->getPartition( ComplexityRisk::RISK_VERY_HIGH );
	}
}