<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Metrics\Complexity\ComplexityAnalyzer;
use Mend\Metrics\Complexity\ComplexityReport;
use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Report\ReportBuilder;
use Mend\Source\Code\Model\Method;
use Mend\Source\Code\Model\MethodArray;
use Mend\Metrics\Complexity\ComplexityRisk;
use Mend\Metrics\Report\Partition\MethodPartition;
use Mend\Metrics\Volume\VolumeReport;

class ComplexityReportBuilder extends ReportBuilder {
	/**
	 * @see ReportBuilder::init()
	 */
	protected function init() {
		$this->setReport( new ComplexityReport() );
	}

	/**
	 * Analyzes the complexity.
	 *
	 * @param EntityReport $entityReport
	 *
	 * @return ComplexityReportBuilder
	 */
	public function analyzeComplexity( EntityReport $entityReport, VolumeReport $volumeReport ) {
		$complexityAnalyzer = new ComplexityAnalyzer();
		$methods = $entityReport->methods()->getMethods();

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$factory = $this->getFactoryByNode( $method->getNode() );
			$mapper = $factory->createNodeMapper( $factory );
			$result = $complexityAnalyzer->computeComplexity( $method, $mapper );
			$method->complexity( $result );
		}

		$this->partition( $methods, $volumeReport );

		return $this;
	}

	/**
	 * Partitions the given methods into risk categories.
	 *
	 * @param MethodArray $methods
	 * @param VolumeReport $volumeReport
	 */
	private function partition( MethodArray $methods, VolumeReport $volumeReport ) {
		$report = $this->getReport();
		/* @var $report ComplexityReport */

		$lows = new MethodArray();
		$moderates = new MethodArray();
		$highs = new MethodArray();
		$veryHighs = new MethodArray();

		$lowCount = 0;
		$moderateCount = 0;
		$highCount = 0;
		$veryHighCount = 0;

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$level = $method->complexity()->getLevel();

			switch ( $level ) {
				case ComplexityRisk::RISK_LOW:
					$lows[] = $method;
					$lowCount += $method->unitSize()->getUnitSize();
					break;

				case ComplexityRisk::RISK_MODERATE:
					$moderates[] = $method;
					$moderateCount += $method->unitSize()->getUnitSize();
					break;

				case ComplexityRisk::RISK_HIGH:
					$highs[] = $method;
					$highCount += $method->unitSize()->getUnitSize();
					break;

				case ComplexityRisk::RISK_VERY_HIGH:
					$veryHighs[] = $method;
					$veryHighCount += $method->unitSize()->getUnitSize();
					break;
			}
		}

		$totalLines = $volumeReport->totalLinesOfCode()->getAbsolute();

		$report->low( new MethodPartition( $lowCount, $lowCount / $totalLines * 100, $lows ) );
		$report->moderate( new MethodPartition( $moderateCount, $moderateCount / $totalLines * 100, $moderates ) );
		$report->high( new MethodPartition( $highCount, $highCount / $totalLines * 100, $highs ) );
		$report->veryHigh( new MethodPartition( $veryHighCount, $veryHighCount / $totalLines * 100, $veryHighs ) );
	}
}