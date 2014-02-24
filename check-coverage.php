#!/usr/bin/env php
<?php
const COLOR_FAIL = "\e[41m";
const COLOR_OK = "\e[42m";
const COLOR_RESET = "\e[49m";
const TEXT_COLOR_FAIL = "\e[97m";
const TEXT_COLOR_OK = "\e[30m";
const TEXT_COLOR_RESET = "\e[0m";

function fail( $message ) {
	fwrite( STDERR, COLOR_FAIL . TEXT_COLOR_FAIL . $message . TEXT_COLOR_RESET . COLOR_RESET . PHP_EOL );
}

function success( $message ) {
	fwrite( STDOUT, COLOR_OK . TEXT_COLOR_OK . $message . TEXT_COLOR_RESET . COLOR_RESET . PHP_EOL );
}

$inputFile = $argv[ 1 ];
$threshold = min( 100, max( 0, (int) $argv[ 2 ] ) );

if ( !file_exists( $inputFile ) ) {
	fwrite( STDERR, "ERROR: No such file: '{$inputFile}'.\n" );
	exit( 1 );
}

if ( empty( $threshold ) ) {
	fwrite( STDERR, "ERROR: Invalid minimum coverage: '{$threshold}'.\n" );
	exit( 1 );
}

$xml = new \SimpleXMLElement( file_get_contents( $inputFile ) );
$metrics = $xml->xpath( '//metrics' );

$totalElements = 0;
$coveredElements = 0;

foreach ( $metrics as $metric ) {
	$totalElements += (int) $metric[ 'elements' ];
	$coveredElements += (int) $metric[ 'coveredelements' ];
}

$coverage = round( $coveredElements / $totalElements * 100, 2 );

if ( $coverage < $threshold ) {
	fail( "Failure (code coverage is {$coverage}%, need at least {$threshold}%)" );
	exit( 1 );
}

success( "OK (code coverage is {$coverage}%, of at least {$threshold}%)" );
exit( 0 );
