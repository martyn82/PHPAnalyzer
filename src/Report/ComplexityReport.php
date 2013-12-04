<?php
namespace Report;

use Report\Partition;
use Report\Rank;

class ComplexityReport implements Rank {
	private $low;
	private $moderate;
	private $high;
	private $veryHigh;

	public function __construct( Partition $low, Partition $moderate, Partition $high, Partition $veryHigh ) {
		$this->low = $low;
		$this->moderate = $moderate;
		$this->high = $high;
		$this->veryHigh = $veryHigh;
	}

	public function low() {
		return $this->low;
	}

	public function moderate() {
		return $this->moderate;
	}

	public function high() {
		return $this->high;
	}

	public function veryHigh() {
		return $this->veryHigh;
	}

	public function getRank() {
		if ( $this->moderate()->getRelativeLOC() <= 25 && $this->high->getRelativeLOC() == 0 && $this->veryHigh->getRelativeLOC() == 0 ) {
			return self::RANK_VERY_GOOD;
		}

		if ( $this->moderate->getRelativeLOC() <= 30 && $this->high->getRelativeLOC() <= 5 && $this->veryHigh->getRelativeLOC() == 0 ) {
			return self::RANK_GOOD;
		}

		if ( $this->moderate->getRelativeLOC() <= 40 && $this->high->getRelativeLOC() <= 10 && $this->veryHigh->getRelativeLOC() <= 0 ) {
			return self::RANK_OK;
		}

		if ( $this->moderate->getRelativeLOC() <= 50 && $this->high->getRelativeLOC() <= 15 && $this->veryHigh->getRelativeLOC() <= 5 ) {
			return self::RANK_BAD;
		}

		return self::RANK_VERY_BAD;
	}
}