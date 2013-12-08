<?php
namespace Metrics\Report;

use \Metrics\Model\DuplicationArray;

class DuplicationReport implements Rank {
	private $absoluteLOC;
	private $relativeLOC;
	private $duplications;

	/**
	 * Constructs a new Duplication report.
	 *
	 * @param integer $absoluteLOC
	 * @param float $relativeLOC
	 * @param DuplicationArray $duplications
	 */
	public function __construct( $absoluteLOC, $relativeLOC, DuplicationArray $duplications ) {
		$this->absoluteLOC = (int) $absoluteLOC;
		$this->relativeLOC = (float) $relativeLOC;
		$this->duplications = $duplications;
	}

	/**
	 * Retrieves absolute lines of code.
	 *
	 * @return integer
	 */
	public function getAbsoluteLOC() {
		return $this->absoluteLOC;
	}

	/**
	 * Retrieves relative lines of code.
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
	 * Retrieves the duplications.
	 *
	 * @return \Metrics\Model\DuplicationArray
	 */
	public function getDuplications() {
		return $this->duplications;
	}

	public function getRank() {
		$relativeDuplications = $this->getRelativeLOC();

		if ( $relativeDuplications >= 20 ) {
			return self::RANK_VERY_BAD;
		}

		if ( $relativeDuplications >= 10 ) {
			return self::RANK_BAD;
		}

		if ( $relativeDuplications >= 5 ) {
			return self::RANK_OK;
		}

		if ( $relativeDuplications >= 3 ) {
			return self::RANK_GOOD;
		}

		return self::RANK_VERY_GOOD;
	}
}