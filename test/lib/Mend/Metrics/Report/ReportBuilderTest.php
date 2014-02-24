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
		$root = $this->getMock( '\Mend\IO\FileSystem\Directory', array( 'getName' ), array(), '', false );

		$root->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( 'test:///foo' ) );

		$project = $this->getMock(
			'\Mend\Metrics\Project\Project',
			array( 'getRoot' ),
			array( 'key', 'name', $root ),
			'',
			false
		);

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

		$phpNode = $this->getMock( '\Mend\Parser\Node\PHPNode', array(), array(), '', false );
		self::assertEquals( $factory, $builder->getFactoryByNode( $phpNode ) );

		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getExtension' ), array(), '', false );

		$file->expects( self::any() )
			->method( 'getExtension' )
			->will( self::returnValue( FactoryCreator::EXTENSION_PHP ) );

		self::assertEquals( $factory, $builder->getFactoryByFile( $file ) );

		$extractor = $builder->getEntityExtractor( $file );
		self::assertInstanceOf( '\Mend\Source\Extract\EntityExtractor', $extractor );

		\FileSystem::setReadDirResult(
			array(
				'.' => \FileSystem::DIR_MODE,
				'..' => \FileSystem::DIR_MODE,
				'foo' => \FileSystem::FILE_MODE
			)
		);

		self::assertEquals( 1, count( $builder->getFiles() ) );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testGetFactoryByUnknownNode() {
		$project = $this->getMock( '\Mend\Metrics\Project\Project', array(), array(), '', false );
		$builder = new DummyReportBuilder( $project );

		$node = $this->getMock( '\Mend\Parser\Node\Node', array(), array(), '', false );
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
