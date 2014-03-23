<?php
namespace Mend\Metrics\Duplication;

use Mend\Metrics\Report\Partition\CodeBlockPartition;

class DuplicationReportTest extends \TestCase {
	public function testAccessors() {
		$report = new DuplicationReport();
		$empty = CodeBlockPartition::createEmpty();

		self::assertEquals( $empty, $report->duplications() );

		$blocks = $this->getMock( '\Mend\Metrics\Duplication\CodeBlockTable' );

		$duplications = $this->getMockBuilder( '\Mend\Metrics\Report\Partition\CodeBlockPartition' )
			->setConstructorArgs( array( 12, 10, $blocks ) )
			->getMock();

		$report->duplications( $duplications );

		self::assertEquals( $duplications, $report->duplications() );

		$expectedArray = array(
			DuplicationReport::PARTITION_KEY => $duplications->toArray()
		);

		self::assertEquals( $expectedArray, $report->toArray() );
	}
}
