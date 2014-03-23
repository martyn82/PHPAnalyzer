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
		$files[] = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->disableOriginalConstructor()
			->getMock();

		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->disableOriginalConstructor()
			->getMock();

		$packages = new PackageArray();
		$packages[] = Package::createDefault();

		$methods = new MethodArray();
		$methods[] = $this->getMockBuilder( '\Mend\Source\Code\Model\Method' )
			->disableOriginalConstructor()
			->getMock();

		$classes = new ClassModelArray();
		$classes[] = $this->getMockBuilder( '\Mend\Source\Code\Model\ClassModel' )
			->disableOriginalConstructor()
			->getMock();

		$sourceExtractor = $this->getMockBuilder( '\Mend\Source\Extract\SourceFileExtractor' )
			->disableOriginalConstructor()
			->getMock();

		$entityExtractor = $this->getMockBuilder( '\Mend\Source\Extract\EntityExtractor' )
			->setMethods( array( 'getPackages', 'getClasses', 'getMethods', 'getSourceExtractor' ) )
			->disableOriginalConstructor()
			->getMock();

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