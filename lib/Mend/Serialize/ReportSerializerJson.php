<?php
namespace Serialize;

use \Metrics\Report\Report;

class ReportSerializerJson extends ReportSerializer {
	/**
	 * Serializes the given report.
	 *
	 * @param \Metrics\Report\Report $report
	 *
	 * @return string
	 */
	public function serialize( Report $report ) {

	}

	/**
	 * Unserializes the given report.
	 *
	 * @param string $string
	 *
	 * @return \Metrics\Report\Report
	 */
	public function unserialize( $string ) {

	}
}