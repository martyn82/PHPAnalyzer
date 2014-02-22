<?php
namespace Mend\Metrics\Project;

class ProjectReportTest extends \TestCase {
	public function testAddReport() {
		$root = $this->getMock( '\Mend\IO\FileSystem\Directory', array(), array( '/tmp' ) );
		$project = $this->getMock( '\Mend\Metrics\Project\Project', array(), array( 'name', 'key', $root ) );
		$report = $this->getMock( '\Mend\Metrics\Report\Report', array(), array() );

		$projectReport = new ProjectReport( $project );
		$projectReport->addReport( 'report1', $report );

		self::assertEquals( $report, $projectReport->getReport( 'report1' ) );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testGetUndefinedReport() {
		$root = $this->getMock( '\Mend\IO\FileSystem\Directory', array(), array( '/foo' ) );
		$project = $this->getMock( '\Mend\Metrics\Project\Project', array(), array( 'name', 'key', $root ) );
		$report = new ProjectReport( $project );
		$report->getReport( 'report1' );
	}
}
