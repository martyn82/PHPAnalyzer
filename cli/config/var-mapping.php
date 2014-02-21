<?php

use Mend\Metrics\Complexity\ComplexityReport;
use Mend\Metrics\Duplication\DuplicationReport;
use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Report\ReportType;
use Mend\Metrics\UnitSize\UnitSizeReport;
use Mend\Metrics\Volume\VolumeReport;

/**
 * Maps given report to their variables.
 *
 * @param ProjectReport $report
 *
 * @return array
 */
$mapVariables = function ( ProjectReport $report ) {
	$project = $report->getProject();
	$complexities = $report->getReport( ReportType::REPORT_COMPLEXITY );
	$duplications = $report->getReport( ReportType::REPORT_DUPLICATION );
	$entities = $report->getReport( ReportType::REPORT_ENTITY );
	$unitSizes = $report->getReport( ReportType::REPORT_UNITSIZE );
	$volume = $report->getReport( ReportType::REPORT_VOLUME );

	return array(
		// project values
		"project.name" => $project->getName(),
		"project.root.name" => $project->getBaseFolder(),
		"project.root.path" => $project->getRoot()->getName(),

		// project report
		"report.datetime" => $report->getDateTime()->format( 'r' ),

		// entity report
		"entities.files.count" => count( $entities->files()->getFiles() ),
		"entities.packages.count" => count( $entities->packages()->getPackages() ),
		"entities.classes.count" => count( $entities->classes()->getClasses() ),
		"entities.methods.count" => count( $entities->methods()->getMethods() ),

		// volume report
		"volume.lines.abs" => $volume->totalLines()->getAbsolute(),
		"volume.lines.rel" => round( $volume->totalLines()->getRelative(), 2 ),
		"volume.loc.abs" => $volume->totalLinesOfCode()->getAbsolute(),
		"volume.loc.rel" => round( $volume->totalLinesOfCode()->getRelative(), 2 ),
		"volume.comments.abs" => $volume->totalLinesOfComments()->getAbsolute(),
		"volume.comments.rel" => round( $volume->totalLinesOfComments()->getRelative(), 2 ),
		"volume.blank.abs" => $volume->totalBlankLines()->getAbsolute(),
		"volume.blank.rel" => round( $volume->totalBlankLines()->getRelative(), 2 ),

		// duplication report
		"duplication.clones.count" => count( $duplications->duplications()->getBlocks() ),
		"duplication.lines.abs" => $duplications->duplications()->getAbsolute(),
		"duplication.lines.rel" => round( $duplications->duplications()->getRelative(), 2 ),

		// complexity report
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

		// unit size report
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
};
