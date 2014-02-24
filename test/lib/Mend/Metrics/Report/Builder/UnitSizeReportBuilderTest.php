<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Network\Web\Url;
use Mend\Source\Code\Model\MethodArray;
use Mend\Metrics\UnitSize\UnitSizeCategory;

class UnitSizeReportBuilderTest extends \TestCase {
	/**
	 * @dataProvider sizeProvider
	 *
	 * @param integer $methodSize
	 * @param integer $sizeCategory
	 */
	public function testAnalyzeUnitSize( $methodSize, $sizeCategory ) {
		$unitSizeResult = $this->getMock(
			'\Mend\Metrics\UnitSize\UnitSizeResult',
			array( 'getUnitSize', 'getCategory' ),
			array( $methodSize, $sizeCategory )
		);

		$unitSizeResult->expects( self::any() )
			->method( 'getUnitSize' )
			->will( self::returnValue( $methodSize ) );

		$unitSizeResult->expects( self::any() )
			->method( 'getCategory' )
			->will( self::returnValue( $sizeCategory ) );

		$url = Url::createFromString( 'test:///foo/bar.php' );
		$sourceUrl = $this->getMock( '\Mend\Source\Code\Location\SourceUrl', array( 'getFileName' ), array( $url ) );

		$sourceUrl->expects( self::any() )
			->method( 'getFileName' )
			->will( self::returnValue( 'test:///foo/bar.php' ) );

		$method = $this->getMock(
			'\Mend\Source\Code\Model\Method',
			array( 'getSourceUrl', 'unitSize' ),
			array(),
			'',
			false
		);

		$method->expects( self::any() )
			->method( 'getSourceUrl' )
			->will( self::returnValue( $sourceUrl ) );

		$method->expects( self::any() )
			->method( 'unitSize' )
			->will( self::returnValue( $unitSizeResult ) );

		$methods = new MethodArray();
		$methods[] = $method;

		$methodPartition = $this->getMock(
			'\Mend\Metrics\Report\Partition\MethodPartition',
			array( 'getMethods' ),
			array(),
			'',
			false
		);

		$methodPartition->expects( self::any() )
			->method( 'getMethods' )
			->will( self::returnValue( $methods ) );

		$entityReport = $this->getMock(
			'\Mend\Metrics\Project\EntityReport',
			array( 'methods' )
		);

		$entityReport->expects( self::any() )
			->method( 'methods' )
			->will( self::returnValue( $methodPartition ) );

		$totalLinesOfCode = $this->getMock(
			'\Mend\Metrics\Report\Partition\CodePartition',
			array( 'getAbsolute' ),
			array(),
			'',
			false
		);

		$totalLinesOfCode->expects( self::any() )
			->method( 'getAbsolute' )
			->will( self::returnValue( 10 ) );

		$volumeReport = $this->getMock(
			'\Mend\Metrics\Volume\VolumeReport',
			array( 'totalLinesOfCode' ),
			array(),
			'',
			false
		);

		$volumeReport->expects( self::any() )
			->method( 'totalLinesOfCode' )
			->will( self::returnValue( $totalLinesOfCode ) );

		$project = $this->getMock( '\Mend\Metrics\Project\Project' , array(), array(), '', false );

		$builder = new UnitSizeReportBuilder( $project );

		$self = $builder->analyzeUnitSize( $entityReport, $volumeReport );

		self::assertEquals( $builder, $self );
		self::assertInstanceOf( '\Mend\Metrics\UnitSize\UnitSizeReport', $builder->getReport() );
	}

	public function sizeProvider() {
		return array(
			array( 10, UnitSizeCategory::SIZE_SMALL ),
			array( 20, UnitSizeCategory::SIZE_MEDIUM ),
			array( 50, UnitSizeCategory::SIZE_LARGE ),
			array( 51, UnitSizeCategory::SIZE_VERY_LARGE )
		);
	}
}