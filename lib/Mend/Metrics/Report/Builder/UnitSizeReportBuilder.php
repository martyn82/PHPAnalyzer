<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Report\ReportBuilder;
use Mend\Metrics\UnitSize\UnitSizeAnalyzer;
use Mend\Metrics\UnitSize\UnitSizeReport;

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
	 *
	 * @return UnitSizeReportBuilder
	 */
	public function analyzeUnitSize( EntityReport $entityReport ) {
		$unitSizeAnalyzer = new UnitSizeAnalyzer();
		$methods = $entityReport->methods()->getMethods();

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$result = $unitSizeAnalyzer->calculateMethodSize( $method );
			$method->unitSize( $result );
		}

		return $this;
	}
}