<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Metrics\Complexity\ComplexityAnalyzer;
use Mend\Metrics\Complexity\ComplexityReport;
use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Report\ReportBuilder;

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
	public function analyzeComplexity( EntityReport $entityReport ) {
		$complexityAnalyzer = new ComplexityAnalyzer();
		$methods = $entityReport->methods()->getMethods();

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$factory = $this->getFactoryByNode( $method->getNode() );
			$mapper = $factory->createNodeMapper( $factory );
			$result = $complexityAnalyzer->computeComplexity( $method, $mapper );
			$method->complexity( $result );
		}

		return $this;
	}
}