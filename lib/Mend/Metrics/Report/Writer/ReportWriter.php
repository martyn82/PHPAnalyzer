<?php
namespace Mend\Metrics\Report\Writer;

use Mend\IO\Stream\StreamWriter;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Report\Formatter\ReportFormatter;

class ReportWriter {
	/**
	 * @var ReportFormatter
	 */
	private $formatter;

	/**
	 * @var ProjectReport
	 */
	private $report;

	/**
	 * Constructs a new report writer.
	 *
	 * @param ProjectReport $report
	 * @param ReportFormatter $formatter
	 */
	public function __construct( ProjectReport $report, ReportFormatter $formatter ) {
		$this->report = $report;
		$this->formatter = $formatter;
	}

	/**
	 * Retrieves the project report.
	 *
	 * @return ProjectReport
	 */
	protected function getReport() {
		return $this->report;
	}

	/**
	 * Retrieves the formatter.
	 *
	 * @return ReportFormatter
	 */
	protected function getFormatter() {
		return $this->formatter;
	}

	/**
	 * Converts the report to string.
	 *
	 * @return string
	 */
	public function getReportAsString() {
		return $this->formatter->format( $this->report );
	}

	/**
	 * Converts the report to string.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getReportAsString();
	}

	/**
	 * Writes the report to stream.
	 *
	 * @param StreamWriter $writer
	 */
	public function write( StreamWriter $writer ) {
		$reportString = $this->getReportAsString();
		$shouldClose = false;

		if ( $writer->isClosed() ) {
			$writer->open();
			$shouldClose = true;
		}

		$writer->write( $reportString );

		if ( $shouldClose ) {
			$writer->close();
		}
	}
}