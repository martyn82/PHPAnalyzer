<?php
namespace Metrics\Synthesize;

use \Metrics\Report\Rank;
use \Metrics\Report\Report;

abstract class ReportWriter {
	/**
	 * @var \Metrics\Report\Report
	 */
	private $report;

	/**
	 * Sets the report to write.
	 *
	 * @param \Metrics\Report\Report $report
	 */
	public function setReport( Report $report ) {
		$this->report = $report;
	}

	/**
	 * Retrieves the report.
	 *
	 * @return \Metrics\Report\Report
	 */
	protected function getReport() {
		return $this->report;
	}

	/**
	 * Write and return report.
	 *
	 * @return string
	 */
	abstract public function write();

	/**
	 * Converts a rank to a string value.
	 *
	 * @param integer $rank
	 *
	 * @return string
	 */
	protected function rankToString( $rank ) {
		switch ( (int) $rank ) {
			case Rank::RANK_VERY_BAD:
				return '--';
			case Rank::RANK_BAD:
				return '-';
			case Rank::RANK_OK:
				return 'o';
			case Rank::RANK_GOOD:
				return '+';
		 	case Rank::RANK_VERY_GOOD:
		 		return '++';
		 	default:
		 		return '?';
		}
	}
}