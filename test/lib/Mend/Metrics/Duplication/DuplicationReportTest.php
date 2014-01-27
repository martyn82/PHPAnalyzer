<?php
namespace Mend\Metrics\Duplication;

use Mend\Metrics\Report\Partition\CodeBlockPartition;

class DuplicationReportTest extends \TestCase {
	public function testAccessors() {
		$report = new DuplicationReport();
		$empty = CodeBlockPartition::createEmpty();

		self::assertEquals( $empty, $report->duplications() );

		$blocks = $this->getMock( '\Mend\Metrics\Duplication\CodeBlockTable' );
		$duplications = $this->getMock(
			'\Mend\Metrics\Report\Partition\CodeBlockPartition',
			array(),
			array( 12, 10, $blocks )
		);
		$report->duplications( $duplications );

		self::assertEquals( $duplications, $report->duplications() );
	}
}
