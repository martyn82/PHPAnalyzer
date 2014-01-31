<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Metrics\Report\ReportBuilder;
use Mend\Metrics\Volume\VolumeReport;
use Mend\Metrics\Volume\VolumeAnalyzer;
use Mend\Metrics\Report\Partition\CodePartition;

class VolumeReportBuilder extends ReportBuilder {
	/**
	 * @see ReportBuilder::init()
	 */
	protected function init() {
		$this->setReport( new VolumeReport() );
	}

	/**
	 * Extracts volume facts.
	 *
	 * @return VolumeReportBuilder
	 */
	public function extractVolume() {
		$files = $this->getFiles();

		$volumeAnalyzer = new VolumeAnalyzer( $files );

		$blankLineCount = $volumeAnalyzer->getBlankLinesCount();
		$commentsCount = $volumeAnalyzer->getLinesOfCommentsCount();
		$codeCount = $volumeAnalyzer->getLinesOfCodeCount();
		$totalLineCount = $volumeAnalyzer->getLinesCount();

		$report = $this->getReport();

		/* @var $report VolumeReport */
		$report->totalLines( new CodePartition( $totalLineCount, 100 ) );
		$report->totalLinesOfCode( new CodePartition( $codeCount, $codeCount / $totalLineCount * 100 ) );
		$report->totalBlankLines( new CodePartition( $blankLineCount, $blankLineCount / $totalLineCount * 100 ) );
		$report->totalLinesOfComments( new CodePartition( $commentsCount, $commentsCount / $totalLineCount * 100 ) );

		return $this;
	}
}