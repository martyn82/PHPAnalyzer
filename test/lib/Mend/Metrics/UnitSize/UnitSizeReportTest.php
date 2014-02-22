<?php
namespace Mend\Metrics\UnitSize;

use Mend\Metrics\Report\Partition\MethodPartition;

class UnitSizeReportTest extends \TestCase {
	public function testAccessors() {
		$report = new UnitSizeReport();
		$empty = MethodPartition::createEmpty();

		self::assertEquals( $empty, $report->small() );
		self::assertEquals( $empty, $report->medium() );
		self::assertEquals( $empty, $report->large() );
		self::assertEquals( $empty, $report->veryLarge() );

		$methods = $this->getMock( '\Mend\Source\Code\Model\MethodArray' );
		$small = $this->getMock( '\Mend\Metrics\Report\Partition\MethodPartition', array(), array( 12, 10, $methods ) );
		$report->small( $small );

		self::assertEquals( $small, $report->small() );

		$expectedArray = array(
			UnitSizeCategory::SIZE_LARGE => $report->large()->toArray(),
			UnitSizeCategory::SIZE_MEDIUM => $report->medium()->toArray(),
			UnitSizeCategory::SIZE_SMALL => $report->small()->toArray(),
			UnitSizeCategory::SIZE_VERY_LARGE => $report->veryLarge()->toArray()
		);

		self::assertEquals( $expectedArray, $report->toArray() );
	}
}
