#!/usr/bin/php
<?php
require realpath( __DIR__ . "/src" ) . "/bootstrap.php";

ini_set( 'memory_limit', '1G' );

if ( $argc > 1 ) {
	$root = realpath( $argv[ 1 ] );
}
else {
	$root = getcwd();
}

global $verbose;
$verbose = true;

/* Entry point */
function main ( $root ) {
	$rootDir = new FileSystem\Directory( $root );
	$fileExtension = "php";

	verbose( "Start analysis for directory <{$rootDir->getName()}>" );
	verbose( "Crawling directory for <{$fileExtension}> files..." );

	$crawler = new FileSystem\Crawler( $rootDir );
	$files = $crawler->getFiles( "*.{$fileExtension}" );

	$fileCount = count( $files );
	verbose( "Found {$fileCount} files" );

	verbose( "Create default code normalizer..." );
	$normalizer = Extract\Normalizer\NormalizerFactory::createNormalizerByName( $fileExtension );

	$methodArray = array();

	foreach ( $files as $file ) {
		verbose( "Creating model from file <{$file->getName()}>..." );
		$model = Model\ModelTree::createFromFile( $file );
		$methodArray = array_merge( $methodArray, (array) Extract\ModelExtractor::getMethods( $model ) );
	}

	verbose( "Analyzing method models..." );
	$methods = new Model\MethodArray( $methodArray );

	verbose( "Partitioning methods into complexity classes..." );
	$partitions = Analyze\Complexity::getPartitions( $methods, $normalizer );

	$totalExaminedLOC = 0;
	$lowLOC = array();
	$moderateLOC = array();
	$highLOC = array();
	$veryHighLOC = array();

	foreach ( $partitions as $category => $list ) {
		$absLOC = 0;

		foreach ( $list as $item ) {
			$absLOC += $item->getSize();
		}

		$totalExaminedLOC += $absLOC;

		switch ( $category ) {
			case 'low':
				$lowLOC = $absLOC;
				break;
			case 'moderate':
				$moderateLOC = $absLOC;
				break;
			case 'high':
				$highLOC = $absLOC;
				break;
			case 'veryHigh':
				$veryHighLOC = $absLOC;
				break;
		}
	}

	$low = new Report\Partition( $lowLOC, ( $lowLOC * 100 / $totalExaminedLOC ) );
	$moderate = new Report\Partition( $moderateLOC, ( $moderateLOC * 100 / $totalExaminedLOC ) );
	$high = new Report\Partition( $highLOC, ( $highLOC * 100 / $totalExaminedLOC ) );
	$veryHigh = new Report\Partition( $veryHighLOC, ( $veryHighLOC * 100 / $totalExaminedLOC ) );

	$complexity = new Report\ComplexityReport( $low, $moderate, $high, $veryHigh );

	$methodComplexities = array();

	foreach ( $partitions as $list ) {
		$methodComplexities = array_merge( $methodComplexities, $list );
	}

	verbose( "Partitioning methods into size classes..." );
	$smallLOC = 0;
	$mediumLOC = 0;
	$largeLOC = 0;
	$hugeLOC = 0;

	foreach ( $methodComplexities as $method ) {
		$methodSize = $method->getSize();

		if ( $methodSize <= 10 ) {
			$smallLOC += $methodSize;
		}

		if ( $methodSize > 10 && $methodSize <= 20 ) {
			$mediumLOC += $methodSize;
		}

		if ( $methodSize > 20 && $methodSize <= 50 ) {
			$largeLOC += $methodSize;
		}

		if ( $methodSize > 50 ) {
			$hugeLOC += $methodSize;
		}
	}

	$small = new Report\Partition( $smallLOC, ( $smallLOC * 100 / $totalExaminedLOC ) );
	$medium = new Report\Partition( $mediumLOC, ( $mediumLOC * 100 / $totalExaminedLOC ) );
	$large = new Report\Partition( $largeLOC, ( $largeLOC * 100 / $totalExaminedLOC ) );
	$huge = new Report\Partition( $hugeLOC, ( $hugeLOC * 100 / $totalExaminedLOC ) );
	$unitSize = new Report\UnitSizeReport( $small, $medium, $large, $huge );

	verbose( "Counting lines of code..." );
	$totalLines = Analyze\Volume::getTotalLineCount( $files );
	$totalLOC = Analyze\Volume::getTotalLinesOfCodeCount( $files );
	$volume = new Report\VolumeReport( $totalLines, $totalLOC );

	verbose( "Counting duplicates..." );
	$absoluteDuplication = Analyze\Duplication::getDuplicationCount( $files );
	$relativeDuplication = ( $absoluteDuplication * 100 / $totalLOC );
	$duplication = new Report\DuplicationReport( $absoluteDuplication, $relativeDuplication );

	verbose( "Generating report..." );
	$report = new Report\Report( $volume, $complexity, $unitSize, $duplication );

	echo "PHP Analyzer", PHP_EOL,
		PHP_EOL,
		"----- Analysis results ----", PHP_EOL,
		"Root directory           : ", $rootDir->getName(), PHP_EOL,
		"File extension           : ", $fileExtension, PHP_EOL,
		PHP_EOL,
		PHP_EOL,
		"Volume", PHP_EOL,
		"---------------------------", PHP_EOL,
		"Total lines              : ", $report->volume()->getTotalLines(), PHP_EOL,
		"Total lines of code      : ", $report->volume()->getTotalLinesOfCode(), PHP_EOL,
		"Blank / commented lines  : ", ( $report->volume()->getTotalLines() - $report->volume()->getTotalLinesOfCode() ), PHP_EOL,
		"---------------------------", PHP_EOL,
		"Volume rank              : ", Synthesize\Rank::rankToString( $report->volume()->getRank() ), PHP_EOL,
		PHP_EOL,
		PHP_EOL,
		"Duplication", PHP_EOL,
		"---------------------------", PHP_EOL,
		"Absolute duplicated lines: ", $report->duplication()->getAbsoluteLOC(), PHP_EOL,
		"Relative duplication     : ", $report->duplication()->getRelativeLOC(), "%", PHP_EOL,
		"---------------------------", PHP_EOL,
		"Duplication rank         : ", Synthesize\Rank::rankToString( $report->duplication()->getRank() ), PHP_EOL,
		PHP_EOL,
		PHP_EOL,
		"Method size", PHP_EOL,
		"---------------------------", PHP_EOL,
		"Small sized methods", PHP_EOL,
		"  Absolute lines         : ", $report->unitSize()->small()->getAbsoluteLOC(), PHP_EOL,
		"  Relative lines         : ", $report->unitSize()->small()->getRelativeLOC(), "%", PHP_EOL,
		PHP_EOL,
		"Medium sized methods", PHP_EOL,
		"  Absolute lines         : ", $report->unitSize()->medium()->getAbsoluteLOC(), PHP_EOL,
		"  Relative lines         : ", $report->unitSize()->medium()->getRelativeLOC(), "%", PHP_EOL,
		PHP_EOL,
		"Large sized methods", PHP_EOL,
		"  Absolute lines         : ", $report->unitSize()->large()->getAbsoluteLOC(), PHP_EOL,
		"  Relative lines         : ", $report->unitSize()->large()->getRelativeLOC(), "%", PHP_EOL,
		PHP_EOL,
		"Very large sized methods", PHP_EOL,
		"  Absolute lines         : ", $report->unitSize()->huge()->getAbsoluteLOC(), PHP_EOL,
		"  Relative lines         : ", $report->unitSize()->huge()->getRelativeLOC(), "%", PHP_EOL,
		"---------------------------", PHP_EOL,
		"Method size rank         : ", Synthesize\Rank::rankToString( $report->unitSize()->getRank() ), PHP_EOL,
		PHP_EOL,
		PHP_EOL,
		"Method complexity", PHP_EOL,
		"---------------------------", PHP_EOL,
		"Low risk method", PHP_EOL,
		"  Absolute lines         : ", $report->complexity()->low()->getAbsoluteLOC(), PHP_EOL,
		"  Relative lines         : ", $report->complexity()->low()->getRelativeLOC(), "%", PHP_EOL,
		PHP_EOL,
		"Moderate risk methods", PHP_EOL,
		"  Absolute lines         : ", $report->complexity()->moderate()->getAbsoluteLOC(), PHP_EOL,
		"  Relative lines         : ", $report->complexity()->moderate()->getRelativeLOC(), "%", PHP_EOL,
		PHP_EOL,
		"High risk methods", PHP_EOL,
		"  Absolute lines         : ", $report->complexity()->high()->getAbsoluteLOC(), PHP_EOL,
		"  Relative lines         : ", $report->complexity()->high()->getRelativeLOC(), "%", PHP_EOL,
		PHP_EOL,
		"Very high risk methods", PHP_EOL,
		"  Absolute lines         : ", $report->complexity()->veryHigh()->getAbsoluteLOC(), PHP_EOL,
		"  Relative lines         : ", $report->complexity()->veryHigh()->getRelativeLOC(), "%", PHP_EOL,
		"---------------------------", PHP_EOL,
		"Complexity rank          : ", Synthesize\Rank::rankToString( $report->complexity()->getRank() ), PHP_EOL,
		PHP_EOL,
		PHP_EOL,
		"Maintainability rankings", PHP_EOL,
		"---------------------------", PHP_EOL,
		"Analyzability            : ", Synthesize\Rank::rankToString( Synthesize\Maintainability::getAnalyzability( $report ) ), PHP_EOL,
		"Changeability            : ", Synthesize\Rank::rankToString( Synthesize\Maintainability::getChangeability( $report ) ), PHP_EOL,
		"Stability                : ", "o", PHP_EOL,
		"Testability              : ", Synthesize\Rank::rankToString( Synthesize\Maintainability::getTestability( $report ) ), PHP_EOL,
		"---------------------------", PHP_EOL,
		"Total                    : ", Synthesize\Rank::rankToString( Synthesize\Maintainability::getMaintainability( $report ) ), PHP_EOL,
		PHP_EOL
	;
};

function verbose( $msg ) {
	global $verbose;

	if ( $verbose ) {
		echo "* {$msg}", PHP_EOL;
	}
}
main( $root );