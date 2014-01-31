<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Report\ReportBuilder;
use Mend\Metrics\UnitSize\UnitSizeAnalyzer;
use Mend\Metrics\UnitSize\UnitSizeReport;
use Mend\Metrics\Volume\VolumeReport;
use Mend\Source\Code\Model\Method;
use Mend\Source\Code\Model\MethodArray;
use Mend\Metrics\UnitSize\UnitSizeCategory;
use Mend\Metrics\Report\Partition\MethodPartition;

class UnitSizeReportBuilder extends ReportBuilder {
	/**
	 * @see ReportBuilder::init()
	 */
	protected function init() {
		$this->setReport( new UnitSizeReport() );
	}

	/**
	 * Analyzes unit size.
	 *
	 * @param EntityReport $entityReport
	 * @param VolumeReport $volumeReport
	 *
	 * @return UnitSizeReportBuilder
	 */
	public function analyzeUnitSize( EntityReport $entityReport, VolumeReport $volumeReport ) {
		$unitSizeAnalyzer = new UnitSizeAnalyzer();
		$methods = $entityReport->methods()->getMethods();

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$result = $unitSizeAnalyzer->calculateMethodSize( $method );
			$method->unitSize( $result );
		}

		$this->sortBySize( $methods );
		$this->partition( $methods, $volumeReport );

		return $this;
	}

	/**
	 * Partitions the given methods into size categories.
	 *
	 * @param MethodArray $methods
	 * @param VolumeReport $volumeReport
	 */
	private function partition( MethodArray $methods, VolumeReport $volumeReport ) {
		$report = $this->getReport();
		/* @var $report UnitSizeReport */

		$smalls = new MethodArray();
		$mediums = new MethodArray();
		$larges = new MethodArray();
		$veryLarges = new MethodArray();

		$smallCount = 0;
		$mediumCount = 0;
		$largeCount = 0;
		$veryLargeCount = 0;

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$category = $method->unitSize()->getCategory();

			switch ( $category ) {
				case UnitSizeCategory::SIZE_SMALL:
					$smalls[] = $method;
					$smallCount += $method->unitSize()->getUnitSize();
					break;

				case UnitSizeCategory::SIZE_MEDIUM:
					$mediums[] = $method;
					$mediumCount += $method->unitSize()->getUnitSize();
					break;

				case UnitSizeCategory::SIZE_LARGE:
					$larges[] = $method;
					$largeCount += $method->unitSize()->getUnitSize();
					break;

				case UnitSizeCategory::SIZE_VERY_LARGE:
					$veryLarges[] = $method;
					$veryLargeCount += $method->unitSize()->getUnitSize();
					break;
			}
		}

		$totalCount = $volumeReport->totalLinesOfCode()->getAbsolute();

		$report->small( new MethodPartition( $smallCount, $smallCount / $totalCount * 100, $smalls ) );
		$report->medium( new MethodPartition( $mediumCount, $mediumCount / $totalCount * 100, $mediums ) );
		$report->large( new MethodPartition( $largeCount, $largeCount / $totalCount * 100, $larges ) );
		$report->veryLarge( new MethodPartition( $veryLargeCount, $veryLargeCount / $totalCount * 100, $veryLarges ) );
	}

	/**
	 * Sorts the given methods by unit size.
	 *
	 * @param MethodArray & $methods
	 */
	private function sortBySize( MethodArray & $methods ) {
		$methods->uasort( function ( Method $a, Method $b ) {
			$sizeA = $a->unitSize()->getUnitSize();
			$sizeB = $b->unitSize()->getUnitSize();

			if ( $sizeA == $sizeB ) {
				return 0;
			}

			return $sizeA > $sizeB ? 1 : -1;
		} );
	}
}