<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Metrics\Duplication\CodeBlockAnalyzer;
use Mend\Metrics\Duplication\CodeBlockExtractor;
use Mend\Metrics\Duplication\DuplicationReport;
use Mend\Metrics\Report\Partition\CodeBlockPartition;
use Mend\Metrics\Report\ReportBuilder;
use Mend\Metrics\Volume\VolumeReport;

class DuplicationReportBuilder extends ReportBuilder {
	/**
	 * @see ReportBuilder::init()
	 */
	protected function init() {
		$this->setReport( new DuplicationReport() );
	}

	/**
	 * Computes duplications.
	 *
	 * @param VolumeReport $volume
	 *
	 * @return DuplicationReportBuilder
	 */
	public function computeDuplications( VolumeReport $volume ) {
		$files = $this->getFiles( $this->getFileExtensions() );

		$codeBlockExtractor = new CodeBlockExtractor();
		$codeBlocks = $codeBlockExtractor->getCodeBlocks( $files );

		$codeBlockAnalyzer = new CodeBlockAnalyzer();
		$codeBlockTable = $codeBlockAnalyzer->findDuplicates( $codeBlocks );

		$duplicatedLines = $codeBlockAnalyzer->getDuplicateLines( $codeBlockTable );
		$totalLines = max( 1, $volume->totalLines()->getAbsolute() );

		$partition = new CodeBlockPartition( $duplicatedLines, $duplicatedLines / $totalLines * 100, $codeBlockTable );

		/* @var $report DuplicationReport */
		$report = $this->getReport();
		$report->duplications( $partition );

		return $this;
	}
}