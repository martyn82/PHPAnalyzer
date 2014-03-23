<?php
namespace Mend\Metrics\Report\Builder;

use Mend\IO\FileSystem\FileArray;

class VolumeReportBuilderTest extends \TestCase {
	public function testExtractVolume() {
		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->disableOriginalConstructor()
			->getMock();

		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getExtension' ), array( 'test:///foo.php' ) );

		$file->expects( self::any() )
			->method( 'getExtension' )
			->will( self::returnValue( 'php' ) );

		$files = new FileArray();
		$files[] = $file;

		$builder = $this->getMock(
			'\Mend\Metrics\Report\Builder\VolumeReportBuilder',
			array( 'getFiles' ),
			array( $project )
		);

		$builder->expects( self::any() )
			->method( 'getFiles' )
			->will( self::returnValue( $files ) );

		$self = $builder->extractVolume();

		self::assertEquals( $builder, $self );
		self::assertInstanceOf( '\Mend\Metrics\Volume\VolumeReport', $builder->getReport() );
	}
}