#!/usr/bin/php
<?php
require_once realpath( __DIR__ . "/app" ) . "/bootstrap.php";

use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;

use Mend\Metrics\Complexity\ComplexityAnalyzer;
use Mend\Metrics\UnitSize\UnitSizeAnalyzer;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReader;
use Mend\Parser\Adapter\PHPParserAdapter;
use Mend\Parser\Node\PHPNodeMapper;
use Mend\Source\Code\Model\Package;
use Mend\Source\Extract\EntityExtractor;

$projectRoot = new Directory( LIB_DIR );
$project = new Project( 'Test', 'test', $projectRoot );
$projectReport = new ProjectReport( $project );

$projectReader = new ProjectReader( $project );
$files = $projectReader->getFiles();

echo "Project: ", $project->getName(), PHP_EOL,
	"\tfiles: ", count( $files ), PHP_EOL;

$mapper = new PHPNodeMapper();
$allPackages = array();
$extractors = array();

foreach ( $files as $file ) {
	$entityExtractor = new EntityExtractor( $file, new PHPParserAdapter(), $mapper );
	$allPackages = array_merge( $allPackages, (array) $entityExtractor->getPackages() );
	$extractors[ $file->getName() ] = $entityExtractor;
}

$packages = array_reduce(
	$allPackages,
	function ( array $result, Package $package ) {
		$name = (string) $package->getName();
		if ( array_key_exists( $name, $result ) ) {
			$result[ $name ][] = $package;
		}
		else {
			$result[ $name ] = array( $package );
		}

		return $result;
	},
	array()
);

$complexityAnalyzer = new ComplexityAnalyzer();
$unitSizeAnalyzer = new UnitSizeAnalyzer();

foreach ( $packages as $name => $bucket ) {
	$classes = array_reduce(
		$bucket,
		function ( array $result, Package $package ) use ( $extractors ) {
			$extractor = $extractors[ $package->getSourceUrl()->getFilename() ];
			$classes = $extractor->getClasses( $package );
			$package->classes( $classes );
			$result = array_merge( $result, (array) $classes );
			return $result;
		},
		array()
	);

	echo "\t", $name, PHP_EOL,
		"\t\tclasses: ", count( $classes ), PHP_EOL;

	foreach ( $classes as $class ) {
		$extractor = $extractors[ $class->getSourceUrl()->getFilename() ];
		$methods = $extractor->getMethods( $class );
		$class->methods( $methods );

		echo "\t\t", $class->getName(), PHP_EOL,
			"\t\t\tmethods: ", count( $methods ), PHP_EOL;

		foreach ( $methods as $method ) {
			$complexity = $complexityAnalyzer->computeComplexity( $method, $mapper );
			$method->complexity( $complexity );

			$unitSize = $unitSizeAnalyzer->calculateMethodSize( $method );
			$method->unitSize( $unitSize );

			echo "\t\t\t", $method->getName(), PHP_EOL,
				"\t\t\t\tcomplexity: ", $method->complexity()->getComplexity(), PHP_EOL,
				"\t\t\t\tunitsize  : ", $method->unitSize()->getUnitSize(), PHP_EOL;
		}
	}
}
