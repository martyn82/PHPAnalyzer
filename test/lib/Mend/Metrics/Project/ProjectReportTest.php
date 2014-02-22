<?php
namespace Mend\Metrics\Project;

class ProjectReportTest extends \TestCase {
	public function testAddReport() {
		$root = $this->getMock( '\Mend\IO\FileSystem\Directory', array(), array( '/tmp' ) );
		$project = $this->getMock( '\Mend\Metrics\Project\Project', array(), array( 'name', 'key', $root ) );
		$report = $this->getMock( '\Mend\Metrics\Report\Report', array(), array() );
		$dateTime = new \DateTime();

		$projectReport = new ProjectReport( $project, $dateTime );
		$projectReport->addReport( 'report1', $report );

		self::assertEquals( $report, $projectReport->getReport( 'report1' ) );
		self::assertEquals( $project, $projectReport->getProject() );
		self::assertEquals( $dateTime, $projectReport->getDateTime() );

		self::assertTrue( $projectReport->hasReport( 'report1' ) );
		self::assertFalse( $projectReport->hasReport( 'foo' ) );

		$expectedArray = array(
			'project' => $project->toArray(),
			'dateTime' => $dateTime->format( 'r' ),
			'report1' => $report->toArray()
		);

		self::assertEquals( $expectedArray, $projectReport->toArray() );
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
