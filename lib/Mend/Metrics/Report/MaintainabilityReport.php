<?php
namespace Mend\Metrics\Report;

use \Mend\Metrics\Arrayable;

class MaintainabilityReport implements Arrayable {
	/**
	 * @var Report
	 */
	private $report;

	/**
	 * Constructs a new maintainability report.
	 *
	 * @param Report $report
	 */
	public function __construct( Report $report ) {
		$this->report = $report;
	}

	/**
	 * Retrieves a score for analyzability.
	 *
	 * @return integer
	 */
	public function getAnalyzabilityRank() {
		$ranks = array(
			$this->report->volume()->getRank(),
			$this->report->unitSize()->getRank(),
			$this->report->duplication()->getRank()
		);

		return $this->computeRank( $ranks );
	}

	/**
	 * Retrieves a score for changeability.
	 *
	 * @return integer
	 */
	public function getChangeabilityRank() {
		$ranks = array(
			$this->report->complexity()->getRank(),
			$this->report->duplication()->getRank()
		);

		return $this->computeRank( $ranks );
	}

	/**
	 * Retrieves a score for stability.
	 *
	 * @return integer
	 */
	public function getStabilityRank() {
		return 0;
	}

	/**
	 * Retrieves a score for testability.
	 *
	 * @return integer
	 */
	public function getTestabilityRank() {
		$ranks = array(
			$this->report->complexity()->getRank(),
			$this->report->unitSize()->getRank()
		);

		return $this->computeRank( $ranks );
	}

	/**
	 * Retrieves an overall score.
	 *
	 * @return integer
	 */
	public function getRank() {
		$ranks = array(
			$this->getAnalyzabilityRank(),
			$this->getChangeabilityRank(),
			$this->getStabilityRank(),
			$this->getTestabilityRank()
		);

		return $this->computeRank( $ranks );
	}

	/**
	 * Computes the rank of an array of positive integers.
	 *
	 * @param array $ranks
	 *
	 * @return integer
	 */
	private function computeRank( array $ranks ) {
		$ranks = array_filter( $ranks );
		return (int) round( array_sum( $ranks ) / count( $ranks ) );
	}

	/**
	 * Converts this object to array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'analyzabilityRank' => $this->getAnalyzabilityRank(),
			'changeabilityRank' => $this->getChangeabilityRank(),
			'stabilityRank' => $this->getStabilityRank(),
			'testabilityRank' => $this->getTestabilityRank(),
			'rank' => $this->getRank()
		);
	}
}