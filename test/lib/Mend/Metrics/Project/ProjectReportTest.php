<?php
namespace Mend\Metrics\Project;

class ProjectReportTest extends \TestCase {
	public function testAddReport() {
		$root = $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->setConstructorArgs( array( '/tmp' ) )
			->getMock();

		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->setConstructorArgs( array( 'name', 'key', $root ) )
			->getMock();

		$report = $this->getMock( '\Mend\Metrics\Report\Report' );
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
		$root = $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->setConstructorArgs( array( '/foo' ) )
			->getMock();

		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->setConstructorArgs( array( 'name', 'key', $root ) )
			->getMock();

		$report = new ProjectReport( $project );
		$report->getReport( 'report1' );
	}
}
