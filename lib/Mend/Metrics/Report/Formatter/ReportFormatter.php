<?php
namespace Mend\Metrics\Report\Formatter;

use Mend\Metrics\Project\ProjectReport;

abstract class ReportFormatter {
	/**
	 * Formats the given report.
	 *
	 * @param ProjectReport $report
	 *
	 * @return string
	 */
	abstract public function format( ProjectReport $report );
}