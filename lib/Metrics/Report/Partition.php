<?php
namespace Metrics\Report;

use \Metrics\Model\MethodArray;

class Partition {
	private $absoluteLOC;
	private $relativeLOC;
	private $methods;

	/**
	 * Constructs a new partition.
	 *
	 * @param integer $absoluteLOC
	 * @param float $relativeLOC
	 * @param MethodArray $methods
	 */
	public function __construct( $absoluteLOC, $relativeLOC, MethodArray $methods ) {
		$this->absoluteLOC = (int) $absoluteLOC;
		$this->relativeLOC = (float) $relativeLOC;
		$this->methods = $methods;
	}

	/**
	 * Retrieves the absolute LOC.
	 *
	 * @return integer
	 */
	public function getAbsoluteLOC() {
		return $this->absoluteLOC;
	}

	/**
	 * Retrieves the relative LOC.
	 *
	 * @param integer $decimals
	 *
	 * @return float
	 */
	public function getRelativeLOC( $decimals = null ) {
		if ( !is_null( $decimals ) ) {
			return round( $this->relativeLOC, (int) $decimals );
		}

		return $this->relativeLOC;
	}

	/**
	 * Retrieves the methods.
	 *
	 * @return \Metrics\Model\MethodArray
	 */
	public function getMethods() {
		return $this->methods;
	}
}