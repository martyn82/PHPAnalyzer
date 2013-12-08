<?php
namespace Mend\Metrics\Synthesize;

use \Mend\Metrics\Report\Report;

class ReportSerializerJson extends ReportSerializer {
	/**
	 * Serializes the given report.
	 *
	 * @param Report $report
	 *
	 * @return string
	 */
	public function serialize( Report $report ) {
		return json_encode( $report->toArray() );
	}

	/**
	 * Unserializes the given report.
	 *
	 * @param string $string
	 *
	 * @return Report
	 */
	public function unserialize( $string ) {
		return json_decode( $string );
	}
}