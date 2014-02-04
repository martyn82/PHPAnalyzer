<?php
namespace Mend\Metrics\Report\Formatter;

use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Report\Report;

class JsonReportFormatter extends ReportFormatter {
	/**
	 * @see ReportFormatter::format()
	 */
	public function format( ProjectReport $report ) {
		$reportArray = $this->serializeProjectReport( $report );
		return json_encode( $reportArray, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT );
	}

	/**
	 * Serializes given report to array.
	 *
	 * @param ProjectReport $report
	 *
	 * @return array
	 */
	private function serializeProjectReport( ProjectReport $report ) {
		return $report->toArray();
	}
}