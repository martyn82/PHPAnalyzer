<?php
namespace Mend\Metrics\Report;

use \Mend\Metrics\Arrayable;

class VolumeReport implements Rank, Arrayable {
	private $totalLines;
	private $totalLinesOfCode;
	private $fileCount;
	private $packageCount;
	private $classCount;
	private $methodCount;

	/**
	 * Constructs a new Volume report.
	 *
	 * @param integer $totalLines
	 * @param integer $totalLinesOfCode
	 * @param integer $fileCount
	 * @param integer $packageCount
	 * @param integer $classCount
	 * @param integer $methodCount
	 */
	public function __construct(
		$totalLines,
		$totalLinesOfCode,
		$fileCount,
		$packageCount,
		$classCount,
		$methodCount
	) {
		$this->totalLines = (int) $totalLines;
		$this->totalLinesOfCode = (int) $totalLinesOfCode;
		$this->fileCount = (int) $fileCount;
		$this->packageCount = (int) $packageCount;
		$this->classCount = (int) $classCount;
		$this->methodCount = (int) $methodCount;
	}

	/**
	 * Retrieves file count.
	 *
	 * @return integer§
	 */
	public function getFileCount() {
		return $this->fileCount;
	}

	/**
	 * Retrieves package count.
	 *
	 * @return integer
	 */
	public function getPackageCount() {
		return $this->packageCount;
	}

	/**
	 * Retrieves class count.
	 *
	 * @return integer
	 */
	public function getClassCount() {
		return $this->classCount;
	}

	/**
	 * Retrieves method count.
	 *
	 * @return integer
	 */
	public function getMethodCount() {
		return $this->methodCount;
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
			'fileCount' => $this->fileCount,
			'packageCount' => $this->packageCount,
			'classCount' => $this->classCount,
			'methodCount' => $this->methodCount,
			'rank' => $this->getRank()
		);
	}
}