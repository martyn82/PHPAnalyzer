<?php
namespace Mend\Metrics\Complexity;

use Mend\Metrics\Report\Partition\MethodPartition;

class ComplexityReportTest extends \TestCase {
	public function testAccessors() {
		$report = new ComplexityReport();
		$empty = MethodPartition::createEmpty();

		self::assertEquals( $empty, $report->low() );
		self::assertEquals( $empty, $report->moderate() );
		self::assertEquals( $empty, $report->high() );
		self::assertEquals( $empty, $report->veryHigh() );

		$methods = $this->getMock( '\Mend\Source\Code\Model\MethodArray' );
		$low = $this->getMockBuilder( '\Mend\Metrics\Report\Partition\MethodPartition' )
			->setConstructorArgs( array( 12, 10, $methods ) )
			->getMock();
		$report->low( $low );

		self::assertEquals( $low, $report->low() );
	}

	public function testArrayConversion() {
		$report = new ComplexityReport();

		$expected = array(
			ComplexityRisk::RISK_LOW => $report->low()->toArray(),
			ComplexityRisk::RISK_MODERATE => $report->moderate()->toArray(),
			ComplexityRisk::RISK_HIGH => $report->high()->toArray(),
			ComplexityRisk::RISK_VERY_HIGH => $report->veryHigh()->toArray()
		);

		self::assertEquals( $expected, $report->toArray() );
	}
}