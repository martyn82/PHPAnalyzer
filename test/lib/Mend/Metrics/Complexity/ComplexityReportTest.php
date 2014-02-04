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
		$low = $this->getMock( '\Mend\Metrics\Report\Partition\MethodPartition', array(), array( 12, 10, $methods ) );
		$report->low( $low );

		self::assertEquals( $low, $report->low() );
	}
}