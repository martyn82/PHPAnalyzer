#!/usr/bin/php
<?php
require_once realpath( __DIR__ . "/cli" ) . "/bootstrap.php";

use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\Stream\FileStreamReader;
use Mend\Config\ConfigProvider;
use Mend\Config\IniConfigReader;
use Mend\Metrics\Complexity\ComplexityReport;
use Mend\Metrics\Duplication\DuplicationReport;
use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Report\Formatter\TextReportFormatter;
use Mend\Metrics\Report\ProjectReportBuilder;
use Mend\Metrics\Report\ReportType;
use Mend\Metrics\Report\Writer\ReportWriter;
use Mend\Metrics\UnitSize\UnitSizeReport;
use Mend\Metrics\Volume\VolumeReport;

$configFile = new File( realpath( __DIR__ ) . "/example.config.ini" );
$stream = new FileStreamReader( $configFile );
$configReader = new IniConfigReader( $stream );
$configProvider = new ConfigProvider( $configReader );

$projectKey = $configProvider->getString( 'project:key' );
$projectName = $configProvider->getString( 'project:name' );
$projectRoot = $configProvider->getString( 'project:path' );
$fileExtensions = $configProvider->getArray( 'analysis:extensions' );
$memoryLimit = $configProvider->getString( 'system:memory' );
$templatePath = $configProvider->getString( 'report:template' );

ini_set( 'memory_limit', $memoryLimit );

$project = new Project( $projectName, $projectKey, new Directory( realpath( $projectRoot ) ) );
$builder = new ProjectReportBuilder( $project, $fileExtensions );
$report = $builder
	->extractEntities()
	->extractVolume()
	->analyzeUnitSize()
	->analyzeComplexity()
	->computeDuplications()
	->getReport();

$volume = $report->getReport( ReportType::REPORT_VOLUME );
/* @var $volume VolumeReport */

$entities = $report->getReport( ReportType::REPORT_ENTITY );
/* @var $entities EntityReport */

$duplications = $report->getReport( ReportType::REPORT_DUPLICATION );
/* @var $duplications DuplicationReport */

$complexities = $report->getReport( ReportType::REPORT_COMPLEXITY );
/* @var $complexities ComplexityReport */

$unitSizes = $report->getReport( ReportType::REPORT_UNITSIZE );
/* @var $unitSizes UnitSizeReport */

$templateReader = new FileStreamReader( new File( $templatePath ) );
$templateReader->open();
$template = $templateReader->read();
$templateReader->close();

require_once CLI_DIR . "/config/var-mapping.php";
$variableMapping = mapVariables( $project, $complexities, $duplications, $entities, $unitSizes, $volume );

$formatter = new TextReportFormatter( $template, $variableMapping );
$writer = new ReportWriter( $report, $formatter );

echo $writer->getReportAsString(), PHP_EOL;
