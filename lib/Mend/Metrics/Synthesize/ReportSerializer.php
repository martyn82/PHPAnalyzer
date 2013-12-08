<?php
namespace Mend\Metrics\Synthesize;

use \Mend\Metrics\Report\Report;

abstract class ReportSerializer {
	/**
	 * Serializes the given report.
	 *
	 * @param Report $report
	 *
	 * @return string
	 */
	abstract public function serialize( Report $report );

	/**
	 * Unserializes the given report.
	 *
	 * @param string $string
	 *
	 * @return Report
	 */
	abstract public function unserialize( $string );
}