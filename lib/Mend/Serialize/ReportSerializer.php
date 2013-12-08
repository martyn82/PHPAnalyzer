<?php
namespace Serialize;

use \Metrics\Report\Report;

abstract class ReportSerializer {
	/**
	 * Serializes the given report.
	 *
	 * @param \Metrics\Report\Report $report
	 *
	 * @return string
	 */
	abstract public function serialize( Report $report );

	/**
	 * Unserializes the given report.
	 *
	 * @param string $string
	 *
	 * @return \Metrics\Report\Report
	 */
	abstract public function unserialize( $string );
}