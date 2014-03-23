<?php
namespace Mend\Metrics\Report\Builder;

use Mend\IO\FileSystem\FileArray;

class DuplicationReportBuilderTest extends \TestCase {
	public function testComputeDuplications() {
		$files = new FileArray();

		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->disableOriginalConstructor()
			->getMock();

		$builder = $this->getMock(
			'\Mend\Metrics\Report\Builder\DuplicationReportBuilder',
			array( 'getFiles', 'getFileExtensions' ),
			array( $project )
		);

		$builder->expects( self::any() )
			->method( 'getFiles' )
			->will( self::returnValue( $files ) );

		$totalLines = $this->getMock(
			'\Mend\Metrics\Report\Partition\CodePartition',
			array( 'getAbsolute' ),
			array( 10, 1 )
		);

		$totalLines->expects( self::any() )
			->method( 'getAbsolute' )
			->will( self::returnValue( 10 ) );

		$volumeReport = $this->getMock( '\Mend\Metrics\Volume\VolumeReport', array( 'totalLines' ) );

		$volumeReport->expects( self::any() )
			->method( 'totalLines' )
			->will( self::returnValue( $totalLines ) );

		$self = $builder->computeDuplications( $volumeReport );

		self::assertEquals( $builder, $self );
		self::assertInstanceOf( '\Mend\Metrics\Duplication\DuplicationReport', $builder->getReport() );
	}
}