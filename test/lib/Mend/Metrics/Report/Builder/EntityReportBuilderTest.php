<?php
namespace Mend\Metrics\Report\Builder;

use Mend\IO\FileSystem\FileArray;
use Mend\IO\Stream\NullStreamWriter;
use Mend\Logging\Logger;
use Mend\Logging\StreamHandler;
use Mend\Source\Code\Model\ClassModelArray;
use Mend\Source\Code\Model\MethodArray;
use Mend\Source\Code\Model\Package;
use Mend\Source\Code\Model\PackageArray;

class EntityReportBuilderTest extends \TestCase {
	public function setUp() {
		Logger::registerHandler( new StreamHandler( new NullStreamWriter() ) );
	}

	public function testExtractEntities() {
		$files = new FileArray();
		$files[] = $this->getMock( '\Mend\IO\FileSystem\File', array(), array(), '', false );
		$project = $this->getMock( '\Mend\Metrics\Project\Project', array(), array(), '', false );

		$packages = new PackageArray();
		$packages[] = Package::createDefault();

		$methods = new MethodArray();
		$methods[] = $this->getMock( '\Mend\Source\Code\Model\Method', array(), array(), '', false );

		$classes = new ClassModelArray();
		$classes[] = $this->getMock( '\Mend\Source\Code\Model\ClassModel', array(), array(), '', false );

		$sourceExtractor = $this->getMock(
			'\Mend\Source\Extract\SourceFileExtractor',
			array(),
			array(),
			'',
			false
		);

		$entityExtractor = $this->getMock(
			'\Mend\Source\Extract\EntityExtractor',
			array( 'getPackages', 'getClasses', 'getMethods', 'getSourceExtractor' ),
			array(),
			'',
			false
		);

		$entityExtractor->expects( self::any() )
			->method( 'getPackages' )
			->will( self::returnValue( $packages ) );

		$entityExtractor->expects( self::any() )
			->method( 'getClasses' )
			->will( self::returnValue( $classes ) );

		$entityExtractor->expects( self::any() )
			->method( 'getMethods' )
			->will( self::returnValue( $methods ) );

		$entityExtractor->expects( self::any() )
			->method( 'getSourceExtractor' )
			->will( self::returnValue( $sourceExtractor ) );

		$builder = $this->getMock(
			'\Mend\Metrics\Report\Builder\EntityReportBuilder',
			array( 'getFiles', 'getEntityExtractor' ),
			array( $project )
		);

		$builder->expects( self::any() )
			->method( 'getFiles' )
			->will( self::returnValue( $files ) );

		$builder->expects( self::any() )
			->method( 'getEntityExtractor' )
			->will( self::returnValue( $entityExtractor ) );

		$self = $builder->extractEntities();
		self::assertEquals( $builder, $self );

		self::assertInstanceOf( '\Mend\Metrics\Project\EntityReport', $builder->getReport() );
	}
}