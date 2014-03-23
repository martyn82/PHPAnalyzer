<?php
namespace Mend\Metrics\Volume;

use Mend\Metrics\Report\Partition\CodePartition;

class VolumeReportTest extends \TestCase {
	public function testAccessors() {
		$report = new VolumeReport();
		$partition = $this->getMockBuilder( '\Mend\Metrics\Report\Partition\CodePartition' )
			->setConstructorArgs( array( 305010, 100 ) )
			->getMock();

		$empty = CodePartition::createEmpty();

		self::assertEquals( $empty, $report->totalLines() );

		$report->totalLines( $partition );
		self::assertEquals( $partition, $report->totalLines() );

		$expectedArray = array(
			VolumeType::VOLUME_LINES => $report->totalLines()->toArray(),
			VolumeType::VOLUME_LINES_BLANK => $report->totalBlankLines()->toArray(),
			VolumeType::VOLUME_LINES_OF_CODE => $report->totalLinesOfCode()->toArray(),
			VolumeType::VOLUME_LINES_OF_COMMENTS => $report->totalLinesOfComments()->toArray()
		);

		self::assertEquals( $expectedArray, $report->toArray() );
	}
}
