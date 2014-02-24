<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Metrics\Complexity\ComplexityReport;
use Mend\Metrics\UnitSize\UnitSizeCategory;
use Mend\Network\Web\Url;
use Mend\PHPFactory;
use Mend\Source\Code\Model\MethodArray;
use Mend\Metrics\Complexity\ComplexityRisk;

class ComplexityReportBuilderTest extends \TestCase {
	/**
	 * @dataProvider complexityProvider
	 *
	 * @param integer $complexity
	 * @param integer $complexityCategory
	 * @param integer $methodSize
	 * @param integer $sizeCategory
	 */
	public function testAnalyzeComplexity( $complexity, $complexityCategory, $methodSize, $sizeCategory ) {
		$unitSizeResult = $this->getMock(
			'\Mend\Metrics\UnitSize\UnitSizeResult',
			array( 'getUnitSize' ),
			array( $methodSize, $sizeCategory )
		);

		$unitSizeResult->expects( self::any() )
			->method( 'getUnitSize' )
			->will( self::returnValue( $methodSize ) );

		$complexityResult = $this->getMock(
			'\Mend\Metrics\Complexity\ComplexityResult',
			array( 'getLevel' ),
			array( $complexity, $complexityCategory )
		);

		$complexityResult->expects( self::any() )
			->method( 'getLevel' )
			->will( self::returnValue( $complexityCategory ) );

		$node = $this->getMock( '\Mend\Parser\Node\Node' );

		$method = $this->getMock(
			'\Mend\Source\Code\Model\Method',
			array( 'getNode', 'unitSize', 'complexity' ),
			array(),
			'',
			false
		);

		$method->expects( self::any() )
			->method( 'getNode' )
			->will( self::returnValue( $node ) );

		$method->expects( self::any() )
			->method( 'unitSize' )
			->will( self::returnValue( $unitSizeResult ) );

		$method->expects( self::any() )
			->method( 'complexity' )
			->will( self::returnValue( $complexityResult ) );

		$methods = new MethodArray();
		$methods[] = $method;

		$methodPartition = $this->getMock(
			'\Mend\Metrics\Report\Partition\MethodPartition',
			array( 'getMethods', 'getAbsolute', 'getRelative' ),
			array( 1, 1, $methods )
		);

		$methodPartition->expects( self::any() )
			->method( 'getMethods' )
			->will( self::returnValue( $methods ) );

		$methodPartition->expects( self::any() )
			->method( 'getAbsolute' )
			->will( self::returnValue( 1 ) );

		$methodPartition->expects( self::any() )
			->method( 'getRelative' )
			->will( self::returnValue( 1 ) );

		$entityReport = $this->getMock( '\Mend\Metrics\Project\EntityReport', array( 'methods' ) );

		$entityReport->expects( self::any() )
			->method( 'methods' )
			->will( self::returnValue( $methodPartition ) );

		$codePartition = $this->getMock(
			'\Mend\Metrics\Report\Partition\CodePartition',
			array( 'getRelative', 'getAbsolute' ),
			array( 1, 1 )
		);

		$codePartition->expects( self::any() )
			->method( 'getAbsolute' )
			->will( self::returnValue( 1 ) );

		$codePartition->expects( self::any() )
			->method( 'getRelative' )
			->will( self::returnValue( 1 ) );

		$volumeReport = $this->getMock( '\Mend\Metrics\Volume\VolumeReport', array( 'totalLinesOfCode' ) );

		$volumeReport->expects( self::any() )
			->method( 'totalLinesOfCode' )
			->will( self::returnValue( $codePartition ) );

		$project = $this->getMock( '\Mend\Metrics\Project\Project', array(), array(), '', false );

		$builder = $this->getMock(
			'\Mend\Metrics\Report\Builder\ComplexityReportBuilder',
			array( 'getFactoryByNode' ),
			array( $project )
		);

		$builder->expects( self::any() )
			->method( 'getFactoryByNode' )
			->will( self::returnValue( new PHPFactory() ) );

		$self = $builder->analyzeComplexity( $entityReport, $volumeReport );

		self::assertEquals( $builder, $self );
		self::assertInstanceOf( '\Mend\Metrics\Complexity\ComplexityReport', $builder->getReport() );
	}

	public function complexityProvider() {
		return array(
			array( 10, ComplexityRisk::RISK_LOW,       10, UnitSizeCategory::SIZE_SMALL ),
			array( 20, ComplexityRisk::RISK_MODERATE,  20, UnitSizeCategory::SIZE_MEDIUM ),
			array( 50, ComplexityRisk::RISK_HIGH,      50, UnitSizeCategory::SIZE_LARGE ),
			array( 51, ComplexityRisk::RISK_VERY_HIGH, 51, UnitSizeCategory::SIZE_VERY_LARGE )
		);
	}
}