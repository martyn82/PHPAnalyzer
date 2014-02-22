<?php
namespace Mend\Metrics\Report;

use Mend\Metrics\Project\ProjectReport;
use Mend\Collections\Map;
use Mend\Metrics\Report\Partition\CodePartition;

class ReportTest extends \TestCase {
	public function testAccessors() {
		$report = new DummyReport();

		self::assertEquals( new Map(), $report->getPartitions() );

		$name = 'foo';
		$partition = CodePartition::createEmpty();

		$report->addPartition( $name, $partition );
		self::assertEquals( $partition, $report->getPartition( $name ) );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testGetNonExistent() {
		$report = new DummyReport();
		$report->getPartition( 'foo' );

		self::fail( "Expected exception for getting a non-existent partition." );
	}
}

class DummyReport extends Report {
	public function addPartition( $name, CodePartition $partition ) {
		parent::addPartition( $name, $partition );
	}

	public function getPartitions() {
		return parent::getPartitions();
	}

	public function getPartition( $name ) {
		return parent::getPartition( $name );
	}

	public function toArray() {
		return array(); // this should NOT be tested here
	}
}
