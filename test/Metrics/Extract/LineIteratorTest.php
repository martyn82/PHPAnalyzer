<?php
namespace Metrics\Extract;

class LineIteratorTest extends \TestCase {
	public function testIterator() {
		$lines = array(
			1 => 'Line 1',
			2 => 'Line 2',
			3 => 'Line 3',
			4 => 'Line 4',
			10 => 'Line 10'
		);

		$expectedIterationCount = count( $lines );
		$actualIterationCount = 0;

		$iterator = new LineIterator( $lines );
		$expectedLineNumber = 0;
		$expectedLine = null;

		foreach ( $iterator as $lineNumber => $line ) {
			$expectedLineNumber = key( $lines );
			$expectedLine = current( $lines );

			$this->assertEquals( $expectedLineNumber, $lineNumber );
			$this->assertEquals( $expectedLine, $line );

			next( $lines );
			$actualIterationCount++;
		}

		$this->assertEquals( $expectedIterationCount, $actualIterationCount );
	}

	public function test() {
		$file = new \FileSystem\File( __DIR__ . "/../../../demo/sample.php" );
		$normalizer = \Metrics\Extract\SourceNormalizerFactory::createNormalizerByExtension( $file->getExtension() );
		$model = ModelExtractor::createModelFromFile( $file );
		$methods = ModelExtractor::getMethodsFromModel( $model );

		foreach ( $methods as $method ) {
			$complexity = \Metrics\Analyze\ComplexityAnalyzer::computeComplexity( $method );
			$riskLevel = \Metrics\Analyze\ComplexityAnalyzer::getRiskLevel( $complexity );
			$complexityModel = new \Metrics\Model\ComplexityModel( $complexity, $riskLevel );
			$method->complexity( $complexityModel );

			$unitSize = \Metrics\Analyze\UnitSizeAnalyzer::getUnitSize( $method, $normalizer );
			$sizeLevel = \Metrics\Analyze\UnitSizeAnalyzer::getSizeLevel( $unitSize );
			$unitSizeModel = new \Metrics\Model\UnitSizeModel( $unitSize, $sizeLevel );
			$method->unitSize( $unitSizeModel );
		}

		$files = new \FileSystem\FileArray( array( $file ) );

		$totalLineCount = \Metrics\Analyze\VolumeAnalyzer::getTotalLineCount( $files );
		$totalLOC = \Metrics\Analyze\VolumeAnalyzer::getTotalLinesOfCodeCount( $files );
		$volume = new \Metrics\Synthesize\VolumeReport( $totalLineCount, $totalLOC );

		$smallSizes = new \Metrics\Synthesize\Partition();

		$report = new \Metrics\Synthesize\Report( $volume, $unitSizes );
	}
}