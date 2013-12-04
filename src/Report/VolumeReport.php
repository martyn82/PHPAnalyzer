<?php
namespace Report;

use Report\Rank;

class VolumeReport implements Rank {
	private $totalLines;
	private $totalLinesOfCode;

	public function __construct( $totalLines, $totalLinesOfCode ) {
		$this->totalLines = (int) $totalLines;
		$this->totalLinesOfCode = (int) $totalLinesOfCode;
	}

	public function getTotalLines() {
		return $this->totalLines;
	}

	public function getTotalLinesOfCode() {
		return $this->totalLinesOfCode;
	}

	public function getRank() {
		if ( $this->totalLinesOfCode > 131000 ) {
			return self::RANK_VERY_BAD;
		}

		if ( $this->totalLinesOfCode > 655000 ) {
			return self::RANK_BAD;
		}

		if ( $this->totalLinesOfCode > 246000 ) {
			return self::RANK_OK;
		}

		if ( $this->totalLinesOfCode > 66000 ) {
			return self::RANK_GOOD;
		}

		return self::RANK_VERY_GOOD;
	}
}