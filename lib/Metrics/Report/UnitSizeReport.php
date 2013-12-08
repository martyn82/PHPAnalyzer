<?php
namespace Metrics\Report;

class UnitSizeReport implements Rank {
	private $small;
	private $medium;
	private $large;
	private $veryLarge;

	public function __construct( Partition $small, Partition $medium, Partition $large, Partition $veryLarge ) {
		$this->small = $small;
		$this->medium = $medium;
		$this->large = $large;
		$this->veryLarge = $veryLarge;
	}

	public function small() {
		return $this->small;
	}

	public function medium() {
		return $this->medium;
	}

	public function large() {
		return $this->large;
	}

	public function veryLarge() {
		return $this->veryLarge;
	}

	public function getRank() {
		if ( $this->medium->getRelativeLOC() <= 25 && $this->large->getRelativeLOC() == 0 && $this->veryLarge->getRelativeLOC() == 0 ) {
			return self::RANK_VERY_GOOD;
		}

		if ( $this->medium->getRelativeLOC() <= 30 && $this->large->getRelativeLOC() <= 5 && $this->veryLarge->getRelativeLOC() == 0 ) {
			return self::RANK_GOOD;
		}

		if ( $this->medium->getRelativeLOC() <= 40 && $this->large->getRelativeLOC() <= 10 && $this->veryLarge->getRelativeLOC() == 0 ) {
			return self::RANK_OK;
		}

		if ( $this->medium->getRelativeLOC() <= 50 && $this->large->getRelativeLOC() <= 15 && $this->veryLarge->getRelativeLOC() <= 5 ) {
			return self::RANK_BAD;
		}

		return self::RANK_VERY_BAD;
	}
}