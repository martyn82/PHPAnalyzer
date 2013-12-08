<?php
namespace Metrics\Synthesize;

use \FileSystem\Crawler;
use \FileSystem\Directory;
use \FileSystem\File;
use \FileSystem\FileArray;

use \Metrics\Analyze\ComplexityAnalyzer;
use \Metrics\Analyze\DuplicationAnalyzer;
use \Metrics\Analyze\UnitSizeAnalyzer;
use \Metrics\Analyze\VolumeAnalyzer;

use \Metrics\Extract\ModelExtractor;
use \Metrics\Extract\SourceNormalizerFactory;

use \Metrics\Model\ComplexityModel;
use \Metrics\Model\Method;
use \Metrics\Model\MethodArray;
use \Metrics\Model\Model;
use \Metrics\Model\ModelArray;
use \Metrics\Model\UnitSizeModel;

use \Metrics\Report\ComplexityReport;
use \Metrics\Report\DuplicationReport;
use \Metrics\Report\Partition;
use \Metrics\Report\Report;
use \Metrics\Report\UnitSizeReport;
use \Metrics\Report\VolumeReport;

use \Logging\Logger;

class ReportBuilder {
	/**
	 * Analyzes a directory.
	 *
	 * @param \FileSystem\Directory $directory
	 *
	 * @return \Metrics\Report\Report
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
	 * @param \FileSystem\File $file
	 *
	 * @return \Metrics\Report\Report
	 */
	public static function analyzeFile( File $file ) {
		Logger::info( "Analyze file <{$file->getName()}>..." );
		return self::analyzeFiles( new FileArray( array( $file ) ) );
	}

	/**
	 * Analyzes an array of files.
	 *
	 * @param \FileSystem\FileArray $files
	 *
	 * @return \Metrics\Report\Report
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
	 * @param \FileSystem\FileArray $files
	 *
	 * @return \Metrics\Model\ModelArray
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
	 * @param \Metrics\Model\ModelArray $models
	 *
	 * @return \Metrics\Model\MethodArray
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
	 * @param \Metrics\Model\Method $method
	 *
	 * @return \Metrics\Model\ComplexityModel
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
	 * @param \Metrics\Model\Method $method
	 *
	 * @return \Metrics\Model\UnitSizeModel
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
	 * @param \FileSystem\FileArray $files
	 * @param \Metrics\Model\MethodArray $methods
	 *
	 * @return \Metrics\Report\Report
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
	 * @param \FileSystem\FileArray $files
	 *
	 * @return \Metrics\Report\VolumeReport
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
	 * @param \FileSystem\FileArray $files
	 * @param \Metrics\Report\VolumeReport $volume
	 *
	 * @return \Metrics\Report\DuplicationReport
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
	 * @param \Metrics\Model\MethodArray $methods
	 * @param \Metrics\Report\VolumeReport $volume
	 *
	 * @return \Metrics\Report\UnitSizeReport
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
	 * @param \Metrics\Model\MethodArray $methods
	 * @param \Metrics\Report\VolumeReport $volume
	 *
	 * @return \Metrics\Report\ComplexityReport
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