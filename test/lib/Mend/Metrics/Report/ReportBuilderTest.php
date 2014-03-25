<?php
namespace Mend\Metrics\Report;

use Mend\FactoryCreator;
use Mend\IO\FileSystem\File;
use Mend\Parser\Node\Node;

class ReportBuilderTest extends \TestCase {
	public function setUp() {
		\FileSystem::resetResults();
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	public function testAccessors() {
		$root = $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->setMethods( array( 'getName' ) )
			->disableOriginalConstructor()
			->getMock();

		$root->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( 'test:///foo' ) );

		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->setMethods( array( 'getRoot' ) )
			->setConstructorArgs( array( 'key', 'name', $root ) )
			->disableOriginalConstructor()
			->getMock();

		$project->expects( self::any() )
			->method( 'getRoot' )
			->will( self::returnValue( $root ) );

		$extensions = array( FactoryCreator::EXTENSION_PHP );

		$builder = new DummyReportBuilder( $project, $extensions );

		self::assertEquals( $project, $builder->getProject() );
		self::assertEquals( $extensions, $builder->getFileExtensions() );

		$report = $this->getMock( '\Mend\Metrics\Report\Report' );
		$builder->setReport( $report );

		self::assertInstanceOf( '\Mend\Metrics\Report\Report', $builder->getReport() );
		self::assertEquals( $report, $builder->getReport() );

		$creator = new FactoryCreator();
		$factory = $creator->createFactoryByFileExtension( FactoryCreator::EXTENSION_PHP );

		self::assertEquals( $factory, $builder->getFactoryByType( FactoryCreator::EXTENSION_PHP ) );
		// second call is from cache
		self::assertEquals( $factory, $builder->getFactoryByType( FactoryCreator::EXTENSION_PHP ) );

		$phpNode = $this->getMockBuilder( '\Mend\Parser\Node\PHPNode' )
			->disableOriginalConstructor()
			->getMock();

		self::assertEquals( $factory, $builder->getFactoryByNode( $phpNode ) );

		$file = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->setMethods( array( 'getExtension' ) )
			->disableOriginalConstructor()
			->getMock();

		$file->expects( self::any() )
			->method( 'getExtension' )
			->will( self::returnValue( FactoryCreator::EXTENSION_PHP ) );

		self::assertEquals( $factory, $builder->getFactoryByFile( $file ) );

		$extractor = $builder->getEntityExtractor( $file );
		self::assertInstanceOf( '\Mend\Source\Extract\EntityExtractor', $extractor );

		\FileSystem::setReadDirResult(
			array(
				'.' => \FileSystem::MODE_DIRECTORY,
				'..' => \FileSystem::MODE_DIRECTORY,
				'foo' => \FileSystem::MODE_FILE
			)
		);

		self::assertEquals( 1, count( $builder->getFiles() ) );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testGetFactoryByUnknownNode() {
		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->disableOriginalConstructor()
			->getMock();

		$builder = new DummyReportBuilder( $project );

		$node = $this->getMockBuilder( '\Mend\Parser\Node\Node' )
			->disableOriginalConstructor()
			->getMock();

		$builder->getFactoryByNode( $node );

		self::fail( "Test should have triggered an exception." );
	}
}

class DummyReportBuilder extends ReportBuilder {
	protected function init() { /* no-op */ }

	public function setReport( Report $report ) {
		parent::setReport( $report );
	}

	public function getFileExtensions() {
		return parent::getFileExtensions();
	}

	public function getProject() {
		return parent::getProject();
	}

	public function getEntityExtractor( File $file ) {
		return parent::getEntityExtractor( $file );
	}

	public function getFiles( array $extensions = null ) {
		return parent::getFiles( $extensions );
	}

	public function getFactoryByFile( File $file ) {
		return parent::getFactoryByFile( $file );
	}

	public function getFactoryByNode( Node $node ) {
		return parent::getFactoryByNode( $node );
	}

	public function getFactoryByType( $type ) {
		return parent::getFactoryByType( $type );
	}
}
