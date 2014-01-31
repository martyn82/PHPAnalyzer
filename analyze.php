#!/usr/bin/php
<?php
require_once realpath( __DIR__ . "/app" ) . "/bootstrap.php";

ini_set( 'memory_limit', '2G' );

use Mend\IO\FileSystem\Directory;
use Mend\Metrics\Duplication\DuplicationReport;
use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Report\Formatter\TextReportFormatter;
use Mend\Metrics\Report\ProjectReportBuilder;
use Mend\Metrics\Report\ReportType;
use Mend\Metrics\Report\Writer\ReportWriter;
use Mend\Metrics\UnitSize\UnitSizeReport;
use Mend\Metrics\Volume\VolumeReport;

$projectRoot = new Directory( LIB_DIR );
$project = new Project( 'Test', 'test', $projectRoot );

$fileExtensions = array( 'php' );

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

$template = <<<TPL
Project Metrics
----------------------------------------------
Name           : %project.name%
Location       : %project.root.name%

Entities
----------------------------------------------
Files count    : %entities.files.count%
Packages count : %entities.packages.count%
Classes count  : %entities.classes.count%
Methods count  : %entities.methods.count%

Volume facts
----------------------------------------------
Total lines    : %volume.lines.abs% (%volume.lines.rel% %%)
Lines of code  : %volume.loc.abs% (%volume.loc.rel% %%)
Comments       : %volume.comments.abs% (%volume.comments.rel% %%)
Blank          : %volume.blank.abs% (%volume.blank.rel% %%)

Duplication
----------------------------------------------
Code clones    : %duplication.clones.count%
Line count     : %duplication.lines.abs% (%duplication.lines.rel% %%)

Complexity
----------------------------------------------
Very high risk
Methods count  : %complexity.veryHigh.methods.count%
Line count     : %complexity.veryHigh.lines.abs% (%complexity.veryHigh.lines.rel% %%)

High risk
Methods count  : %complexity.high.methods.count%
Line count     : %complexity.high.lines.abs% (%complexity.high.lines.rel% %%)

Moderate risk
Methods count  : %complexity.moderate.methods.count%
Line count     : %complexity.moderate.lines.abs% (%complexity.moderate.lines.rel% %%)

Low risk
Methods count  : %complexity.low.methods.count%
Line count     : %complexity.low.lines.abs% (%complexity.low.lines.rel% %%)

Unit size
----------------------------------------------
Very large methods
Methods count  : %unitSize.veryLarge.methods.count%
Line count     : %unitSize.veryLarge.lines.abs% (%unitSize.veryLarge.lines.rel% %%)

Large methods
Methods count  : %unitSize.large.methods.count%
Line count     : %unitSize.large.lines.abs% (%unitSize.large.lines.rel% %%)

Medium sized methods
Methods count  : %unitSize.medium.methods.count%
Line count     : %unitSize.medium.lines.abs% (%unitSize.medium.lines.rel% %%)

Small methods
Methods count  : %unitSize.small.methods.count%
Line count     : %unitSize.small.lines.abs% (%unitSize.small.lines.rel% %%)

TPL;

$variableMapping = array(
	"project.name" => $project->getName(),
	"project.root.name" => $project->getRoot()->getName(),

	"entities.files.count" => count( $entities->files()->getFiles() ),
	"entities.packages.count" => count( $entities->packages()->getPackages() ),
	"entities.classes.count" => count( $entities->classes()->getClasses() ),
	"entities.methods.count" => count( $entities->methods()->getMethods() ),

	"volume.lines.abs" => $volume->totalLines()->getAbsolute(),
	"volume.lines.rel" => round( $volume->totalLines()->getRelative(), 2 ),
	"volume.loc.abs" => $volume->totalLinesOfCode()->getAbsolute(),
	"volume.loc.rel" => round( $volume->totalLinesOfCode()->getRelative(), 2 ),
	"volume.comments.abs" => $volume->totalLinesOfComments()->getAbsolute(),
	"volume.comments.rel" => round( $volume->totalLinesOfComments()->getRelative(), 2 ),
	"volume.blank.abs" => $volume->totalBlankLines()->getAbsolute(),
	"volume.blank.rel" => round( $volume->totalBlankLines()->getRelative(), 2 ),

	"duplication.clones.count" => count( $duplications->duplications()->getBlocks() ),
	"duplication.lines.abs" => $duplications->duplications()->getAbsolute(),
	"duplication.lines.rel" => round( $duplications->duplications()->getRelative(), 2 ),

	"complexity.low.methods.count" => count( $complexities->low()->getMethods() ),
	"complexity.low.lines.abs" => $complexities->low()->getAbsolute(),
	"complexity.low.lines.rel" => round( $complexities->low()->getRelative(), 2 ),
	"complexity.moderate.methods.count" => count( $complexities->moderate()->getMethods() ),
	"complexity.moderate.lines.abs" => $complexities->moderate()->getAbsolute(),
	"complexity.moderate.lines.rel" => round( $complexities->moderate()->getRelative(), 2 ),
	"complexity.high.methods.count" => count( $complexities->high()->getMethods() ),
	"complexity.high.lines.abs" => $complexities->high()->getAbsolute(),
	"complexity.high.lines.rel" => round( $complexities->high()->getRelative(), 2 ),
	"complexity.veryHigh.methods.count" => count( $complexities->veryHigh()->getMethods() ),
	"complexity.veryHigh.lines.abs" => $complexities->veryHigh()->getAbsolute(),
	"complexity.veryHigh.lines.rel" => round( $complexities->veryHigh()->getRelative(), 2 ),

	"unitSize.veryLarge.methods.count" => count( $unitSizes->veryLarge()->getMethods() ),
	"unitSize.veryLarge.lines.abs" => $unitSizes->veryLarge()->getAbsolute(),
	"unitSize.veryLarge.lines.rel" => round( $unitSizes->veryLarge()->getRelative(), 2 ),
	"unitSize.large.methods.count" => count( $unitSizes->large()->getMethods() ),
	"unitSize.large.lines.abs" => $unitSizes->large()->getAbsolute(),
	"unitSize.large.lines.rel" => round( $unitSizes->large()->getRelative(), 2 ),
	"unitSize.medium.methods.count" => count( $unitSizes->medium()->getMethods() ),
	"unitSize.medium.lines.abs" => $unitSizes->medium()->getAbsolute(),
	"unitSize.medium.lines.rel" => round( $unitSizes->medium()->getRelative(), 2 ),
	"unitSize.small.methods.count" => count( $unitSizes->small()->getMethods() ),
	"unitSize.small.lines.abs" => $unitSizes->small()->getAbsolute(),
	"unitSize.small.lines.rel" => round( $unitSizes->small()->getRelative(), 2 )
);

$formatter = new TextReportFormatter( $template, $variableMapping );
$writer = new ReportWriter( $report, $formatter );

echo $writer->getReportAsString(), PHP_EOL;
