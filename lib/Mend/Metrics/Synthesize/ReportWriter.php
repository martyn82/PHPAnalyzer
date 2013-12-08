<?php
namespace Mend\Metrics\Synthesize;

use \Mend\Metrics\Report\Rank;
use \Mend\Metrics\Report\Report;

abstract class ReportWriter {
	/**
	 * Write and return report.
	 *
	 * @param Report $report
	 *
	 * @return string
	 */
	abstract public function write( Report $report );

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