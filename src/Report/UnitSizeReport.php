<?php
namespace Report;

use Report\Rank;
use Report\Partition;

class UnitSizeReport implements Rank {
	private $small;
	private $medium;
	private $large;
	private $huge;

	public function __construct( Partition $small, Partition $medium, Partition $large, Partition $huge ) {
		$this->small = $small;
		$this->medium = $medium;
		$this->large = $large;
		$this->huge = $huge;
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

	public function huge() {
		return $this->huge;
	}

	public function getRank() {
		if ( $this->medium->getRelativeLOC() <= 25 && $this->large->getRelativeLOC() == 0 && $this->huge->getRelativeLOC() == 0 ) {
			return self::RANK_VERY_GOOD;
		}

		if ( $this->medium->getRelativeLOC() <= 30 && $this->large->getRelativeLOC() <= 5 && $this->huge->getRelativeLOC() == 0 ) {
			return self::RANK_GOOD;
		}

		if ( $this->medium->getRelativeLOC() <= 40 && $this->large->getRelativeLOC() <= 10 && $this->huge->getRelativeLOC() == 0 ) {
			return self::RANK_OK;
		}

		if ( $this->medium->getRelativeLOC() <= 50 && $this->large->getRelativeLOC() <= 15 && $this->huge->getRelativeLOC() <= 5 ) {
			return self::RANK_BAD;
		}

		return self::RANK_VERY_BAD;
	}
}