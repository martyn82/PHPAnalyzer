<?php
namespace Mend\Metrics\Report;

use \Mend\Metrics\Arrayable;

class VolumeReport implements Rank, Arrayable {
	private $totalLines;
	private $totalLinesOfCode;

	/**
	 * Constructs a new Volume report.
	 *
	 * @param integer $totalLines
	 * @param integer $totalLinesOfCode
	 */
	public function __construct( $totalLines, $totalLinesOfCode ) {
		$this->totalLines = (int) $totalLines;
		$this->totalLinesOfCode = (int) $totalLinesOfCode;
	}

	/**
	 * Retrieves the total lines.
	 *
	 * @return integer
	 */
	public function getTotalLines() {
		return $this->totalLines;
	}

	/**
	 * Retrieves the total lines of code.
	 *
	 * @return integer
	 */
	public function getTotalLinesOfCode() {
		return $this->totalLinesOfCode;
	}

	/**
	 * Retrieves the rank of the volume.
	 *
	 * @return integer
	 */
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

	/**
	 * Converts this object to array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'totalLines' => $this->totalLines,
			'totalLinesOfCode' => $this->totalLinesOfCode,
			'rank' => $this->getRank()
		);
	}
}