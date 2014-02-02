<?php
namespace Mend\Metrics\Volume;

use Mend\Metrics\Report\Partition\CodePartition;

class VolumeReportTest extends \TestCase {
	public function testAccessors() {
		$report = new VolumeReport();
		$partition = $this->getMock( '\Mend\Metrics\Report\Partition\CodePartition', array(), array( 305010, 100 ) );
		$empty = CodePartition::createEmpty();

		self::assertEquals( $empty, $report->totalLines() );

		$report->totalLines( $partition );
		self::assertEquals( $partition, $report->totalLines() );
	}
}
