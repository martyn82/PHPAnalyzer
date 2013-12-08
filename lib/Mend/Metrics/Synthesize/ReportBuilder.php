<?php
namespace Mend\Metrics\Synthesize;

use \Mend\FileSystem\Crawler;
use \Mend\FileSystem\Directory;
use \Mend\FileSystem\File;
use \Mend\FileSystem\FileArray;

use \Mend\Metrics\Analyze\ComplexityAnalyzer;
use \Mend\Metrics\Analyze\DuplicationAnalyzer;
use \Mend\Metrics\Analyze\UnitSizeAnalyzer;
use \Mend\Metrics\Analyze\VolumeAnalyzer;

use \Mend\Metrics\Extract\ModelExtractor;
use \Mend\Metrics\Extract\SourceNormalizerFactory;

use \Mend\Metrics\Model\ComplexityModel;
use \Mend\Metrics\Model\Method;
use \Mend\Metrics\Model\MethodArray;
use \Mend\Metrics\Model\Model;
use \Mend\Metrics\Model\ModelArray;
use \Mend\Metrics\Model\UnitSizeModel;

use \Mend\Metrics\Report\ComplexityReport;
use \Mend\Metrics\Report\DuplicationReport;
use \Mend\Metrics\Report\Partition;
use \Mend\Metrics\Report\Report;
use \Mend\Metrics\Report\UnitSizeReport;
use \Mend\Metrics\Report\VolumeReport;

use \Mend\Logging\Logger;

class ReportBuilder {
	/**
	 * Analyzes a directory.
	 *
	 * @param Directory $directory
	 *
	 * @return Report
	 */
	public static function analyzeDirectory( Directory $directory ) {
		Logger::info( "Analyze directory <{$directory->getName()}>..." );

		$crawler = new Crawler( $directory );
		$files = $crawler->getFiles( "*.php" );

		return self::analyzeFiles( $files );
	}

	/**
	 * Analyzes a file.
	 *
	 * @param File $file
	 *
	 * @return Report
	 */
	public static function analyzeFile( File $file ) {
		Logger::info( "Analyze file <{$file->getName()}>..." );
		return self::analyzeFiles( new FileArray( array( $file ) ) );
	}

	/**
	 * Analyzes an array of files.
	 *
	 * @param FileArray $files
	 *
	 * @return Report
	 */
	public static function analyzeFiles( FileArray $files ) {
		Logger::info( "Analyze files..." );

		$models = self::getModelsFromFiles( $files );
		$methods = self::getMethodsFromModels( $models );

		self::analyzeMethods( $methods );

		return self::createReport( $files, $methods );
	}

	/**
	 * Creates models from files.
	 *
	 * @param FileArray $files
	 *
	 * @return ModelArray
	 */
	private static function getModelsFromFiles( FileArray $files ) {
		Logger::info( "Get models from files..." );

		$models = new ModelArray();

		foreach ( $files as $file ) {
			$models[] = ModelExtractor::createModelFromFile( $file );
		}

		return $models;
	}

	/**
	 * Gathers all methods from the given array of models.
	 *
	 * @param ModelArray $models
	 *
	 * @return MethodArray
	 */
	private static function getMethodsFromModels( ModelArray $models ) {
		Logger::info( "Get methods from models..." );

		return new MethodArray(
			array_reduce(
				(array) $models,
				function ( array $result, Model $model ) {
					return array_merge( $result, (array) ModelExtractor::getMethodsFromModel( $model ) );
				},
				array()
			)
		);
	}

	/**
	 * Analyzes the given methods.
	 *
	 * @param MethodArray $methods
	 */
	private static function analyzeMethods( MethodArray $methods ) {
		Logger::info( "Analyze methods..." );

		foreach ( $methods as $method ) {
			$complexityModel = self::analyzeMethodComplexity( $method );
			$method->complexity( $complexityModel );

			$unitSizeModel = self::analyzeMethodSize( $method );
			$method->unitSize( $unitSizeModel );
		}
	}

	/**
	 * Analyzes the given method's complexity.
	 *
	 * @param Method $method
	 *
	 * @return ComplexityModel
	 */
	private static function analyzeMethodComplexity( Method $method ) {
		Logger::info( "Analyze method complexity <{$method->getName()}>..." );

		$complexity = ComplexityAnalyzer::computeComplexity( $method );
		$riskLevel = ComplexityAnalyzer::getRiskLevel( $complexity );

		return new ComplexityModel( $complexity, $riskLevel );
	}

	/**
	 * Analyzes the given method's size.
	 *
	 * @param Method $method
	 *
	 * @return UnitSizeModel
	 */
	private static function analyzeMethodSize( Method $method ) {
		Logger::info( "Analyze method size <{$method->getName()}>..." );

		$unitSize = UnitSizeAnalyzer::getUnitSize( $method, $normalizer );
		$sizeLevel = UnitSizeAnalyzer::getSizeLevel( $unitSize );

		return new UnitSizeModel( $unitSize, $sizeLevel );
	}

	/**
	 * Creates the report for given files and methods.
	 *
	 * @param FileArray $files
	 * @param MethodArray $methods
	 *
	 * @return Report
	 */
	private static function createReport( FileArray $files, MethodArray $methods ) {
		Logger::info( "Create report for files and methods..." );

		$volumeReport = self::createVolumeReport( $files );

		$duplicationReport = self::createDuplicationReport( $files, $volumeReport );
		$unitSizeReport = self::createUnitSizeReport( $methods, $volumeReport );
		$complexityReport = self::createComplexityReport( $methods, $volumeReport );

		$result = new Report( $volumeReport, $unitSizeReport, $complexityReport, $duplicationReport );

		Logger::info( "Report done." );
		return $result;
	}

	/**
	 * Creates the volume report.
	 *
	 * @param FileArray $files
	 *
	 * @return VolumeReport
	 */
	private static function createVolumeReport( FileArray $files ) {
		Logger::info( "Create volume report for files..." );

		$totalLineCount = VolumeAnalyzer::getTotalLineCount( $files );
		$totalLOC = VolumeAnalyzer::getTotalLinesOfCodeCount( $files );

		return new VolumeReport( $totalLineCount, $totalLOC );
	}

	/**
	 * Creates the duplication report.
	 *
	 * @param FileArray $files
	 * @param VolumeReport $volume
	 *
	 * @return DuplicationReport
	 */
	private static function createDuplicationReport( FileArray $files, VolumeReport $volume ) {
		Logger::info( "Create duplication report for files..." );

		$duplications = DuplicationAnalyzer::getDuplications( $files );
		$absLOC = $duplications->getDuplicatedLinesOfCode();
		$relLOC = ( $absLOC * 100 ) / $volume->getTotalLinesOfCode();

		return new DuplicationReport( $absLOC, $relLOC, $duplications );
	}

	/**
	 * Creates the unit size report.
	 *
	 * @param MethodArray $methods
	 * @param VolumeReport $volume
	 *
	 * @return UnitSizeReport
	 */
	private static function createUnitSizeReport( MethodArray $methods, VolumeReport $volume ) {
		Logger::info( "Create unit size report for methods..." );

		$partitions = array_reduce(
			(array) $methods,
			function ( array $result, Method $method ) {
				$unitSize = $method->unitSize();
				$result[ $unitSize->getLevel() ][ 'loc' ] += $unitSize->getSize();
				$result[ $unitSize->getLevel() ][ 'methods' ][] = $method;
				return $result;
			},
			array(
				1 => array( 'loc' => 0, 'methods' => array() ),
				2 => array( 'loc' => 0, 'methods' => array() ),
				3 => array( 'loc' => 0, 'methods' => array() ),
				4 => array( 'loc' => 0, 'methods' => array() )
			)
		);

		$classes = array();
		$totalLOC = $volume->getTotalLinesOfCode();

		foreach ( $partitions as $partition ) {
			$absoluteLOC = $partition[ 'loc' ];
			$relativeLOC = ( $absoluteLOC * 100 ) / $totalLOC;
			$methods = (array) $partition[ 'methods' ];
			$classes[] = new Partition( $absoluteLOC, $relativeLOC, new MethodArray( $methods ) );
		}

		list( $small, $medium, $large, $veryLarge ) = $classes;
		return new UnitSizeReport( $small, $medium, $large, $veryLarge );
	}

	/**
	 * Creates the complexity report.
	 *
	 * @param MethodArray $methods
	 * @param VolumeReport $volume
	 *
	 * @return ComplexityReport
	 */
	private static function createComplexityReport( MethodArray $methods, VolumeReport $volume ) {
		Logger::info( "Create complexity report for methods..." );

		$partitions = array_reduce(
			(array) $methods,
			function ( array $result, Method $method ) {
				$complexity = $method->complexity();
				$result[ $complexity->getLevel() ][ 'loc' ] += $method->unitSize()->getSize();
				$result[ $complexity->getLevel() ][ 'methods' ][] = $method;
				return $result;
			},
			array(
				1 => array( 'loc' => 0, 'methods' => array() ),
				2 => array( 'loc' => 0, 'methods' => array() ),
				3 => array( 'loc' => 0, 'methods' => array() ),
				4 => array( 'loc' => 0, 'methods' => array() )
			)
		);

		$classes = array();
		$totalLOC = $volume->getTotalLinesOfCode();

		foreach ( $partitions as $partition ) {
			$absoluteLOC = $partition[ 'loc' ];
			$relativeLOC = ( $absoluteLOC * 100 ) / $totalLOC;
			$methods = (array) $partition[ 'methods' ];
 			$classes[] = new Partition( $absoluteLOC, $relativeLOC, new MethodArray( $methods ) );
		}

		list( $low, $moderate, $high, $veryHigh ) = $classes;
		return new ComplexityReport( $low, $moderate, $high, $veryHigh );
	}
}