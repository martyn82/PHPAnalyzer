#!/usr/bin/php
<?php

include realpath( __DIR__ . "/src" ) . "/bootstrap.php";

use Analyze\Volume;

use Extract\Normalizer\NormalizerFactory;

use FileSystem\Crawler;
use FileSystem\Directory;

use Synthesize\Rank;
use Synthesize\Maintainability;

if ( $argc > 1 ) {
	$root = realpath( $argv[ 1 ] );
}
else {
	$root = getcwd();
}

/* Entry point */
function main ( $root ) {	
	$rootDir = new Directory( $root );
	$fileExtension = "php";
	
	$crawler = new Crawler( $rootDir );
	$files = $crawler->getFiles( "*.{$fileExtension}" );
	
	$normalizer = NormalizerFactory::createNormalizerByName( $fileExtension );
	
	$maintainability = Maintainability::computeMaintainability( $rootDir, $normalizer );
	
	$totalLines = Volume::getTotalLineCount( $files );
	$totalLOC = Volume::getTotalLinesOfCodeCount( $files );
	
	echo PHP_EOL, "PHP Analyzer", PHP_EOL,
		"---- Analysis results ----", PHP_EOL,
		"Root path           : ", $rootDir->getName(), PHP_EOL,
		"File extension      : ", $fileExtension, PHP_EOL,
		PHP_EOL,
		"Total lines         : ", $totalLines, PHP_EOL,
		"Total lines of code : ", $totalLOC, PHP_EOL,
		"Blanks/Comments     : ", ( $totalLines - $totalLOC ), PHP_EOL,
		PHP_EOL,
		"Analyzability       : ", Rank::rankToString( Maintainability::computeAnalyzability( $rootDir, $normalizer ) ), PHP_EOL,
		"Changeability       : ", Rank::rankToString( Maintainability::computeChangeability( $rootDir, $normalizer ) ), PHP_EOL,
		"Testability         : ", Rank::rankToString( Maintainability::computeTestability( $rootDir, $normalizer ) ), PHP_EOL,
		"--------------------------", PHP_EOL,
		"Maintainability     : ", Rank::rankToString( $maintainability ), PHP_EOL,
		PHP_EOL
	;
};

main( $root );