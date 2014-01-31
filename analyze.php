#!/usr/bin/php
<?php
require_once realpath( __DIR__ . "/app" ) . "/bootstrap.php";

use Mend\IO\FileSystem\Directory;
use Mend\Metrics\Duplication\DuplicationReport;
use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReader;
use Mend\Metrics\Report\ProjectReportBuilder;
use Mend\Metrics\Report\ReportType;
use Mend\Metrics\Volume\VolumeReport;
use Mend\Source\Code\Model\ClassModel;
use Mend\Source\Code\Model\Method;
use Mend\Source\Code\Model\Package;

$projectRoot = new Directory( LIB_DIR );
$project = new Project( 'Test', 'test', $projectRoot );
$reader = new ProjectReader( $project );
$files = $reader->getFiles();

$builder = new ProjectReportBuilder( $project );
$report = $builder
	->extractEntities()
	->extractVolume()
	->analyzeComplexity()
	->analyzeUnitSize()
	->computeDuplications()
	->getReport();

echo "Project :", $project->getName(), PHP_EOL,
	"Location: ", $project->getRoot()->getName(), PHP_EOL;

$volume = $report->getReport( ReportType::REPORT_VOLUME );
/* @var $volume VolumeReport */

$entities = $report->getReport( ReportType::REPORT_ENTITY );
/* @var $entities EntityReport */

$duplications = $report->getReport( ReportType::REPORT_DUPLICATION );
/* @var $duplications DuplicationReport */

echo
	"Total lines     : ", $volume->totalLines()->getAbsolute(), PHP_EOL,
	"Total comments  : ", $volume->totalLinesOfComments()->getAbsolute(), " (", $volume->totalLinesOfComments()->getRelative(), "%)", PHP_EOL,
	"Total blanks    : ", $volume->totalBlankLines()->getAbsolute(), " (", $volume->totalBlankLines()->getRelative(), "%)", PHP_EOL,
	"Total LOC       : ", $volume->totalLinesOfCode()->getAbsolute(), " (", $volume->totalLinesOfCode()->getRelative(), "%)", PHP_EOL,
	"No. packages    : ", count( $entities->packages()->getPackages() ), PHP_EOL,
	"No. classes     : ", count( $entities->classes()->getClasses() ), PHP_EOL,
	"No. methods     : ", count( $entities->methods()->getMethods() ), PHP_EOL,
	"Code clones     : ", count( $duplications->duplications()->getBlocks() ), PHP_EOL,
	"Duplicated lines: ", $duplications->duplications()->getAbsolute(), " (", $duplications->duplications()->getRelative(), "%)", PHP_EOL;

$packages = $entities->packages()->getPackages();

foreach ( $packages as $packageName => $bucket ) {
	echo $packageName, PHP_EOL;

	$classes = array_reduce(
		$bucket,
		function ( array $result, Package $package ) {
			$result = array_merge( $result, (array) $package->classes() );
			return $result;
		},
		array()
	);

	foreach ( $classes as $class ) {
		/* @var $class ClassModel */
		echo "\t", $class->getName(), PHP_EOL;

		foreach ( $class->methods() as $method ) {
			/* @var $method Method */
			echo "\t\t", $method->getName(), PHP_EOL,
				"\t\t\tcomplexity: ", $method->complexity()->getComplexity(), " [", $method->complexity()->getLevel(), "]", PHP_EOL,
				"\t\t\tunit size : ", $method->unitSize()->getUnitSize(), " [", $method->unitSize()->getCategory(), "]", PHP_EOL;
		}
	}
}
