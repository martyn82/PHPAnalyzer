<?php
namespace Mend\Metrics\Report;

use Mend\IO\Stream\NullStreamWriter;
use Mend\Logging\Logger;
use Mend\Logging\StreamHandler;

class ProjectReportBuilderTest extends \TestCase {
	public function setUp() {
		Logger::registerHandler( new StreamHandler( new NullStreamWriter() ) );
	}

	public function testProjectReportBuilder() {
		$root = $this->getMock( '\Mend\IO\FileSystem\Directory', array( 'getName' ), array( 'test:///foo' ) );
		$root->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( 'test:///foo' ) );

		$project = $this->getMock( '\Mend\Metrics\Project\Project', array( 'getRoot' ), array( 'key', 'name', $root ) );
		$project->expects( self::any() )
			->method( 'getRoot' )
			->will( self::returnValue( $root ) );

		$builder = new DummyProjectReportBuilder( $project );

		self::assertNotNull( $builder );
		self::assertEquals( $project, $builder->getProject() );
		self::assertInstanceOf( '\Mend\Metrics\Project\ProjectReport', $builder->getReport() );

		$self = $builder->analyzeComplexity();
		self::assertEquals( $builder, $self );

		$self = $builder->analyzeUnitSize();
		self::assertEquals( $builder, $self );

		$self = $builder->computeDuplications();
		self::assertEquals( $builder, $self );

		$self = $builder->extractEntities();
		self::assertEquals( $builder, $self );

		$self = $builder->extractVolume();
		self::assertEquals( $builder, $self );
	}
}

class DummyProjectReportBuilder extends ProjectReportBuilder {
	public function getProject() {
		return parent::getProject();
	}
}
