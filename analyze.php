#!/usr/bin/php
<?php
require realpath( __DIR__ . "/app" ) . "/bootstrap.php";

use \Mend\FileSystem\Directory;
use \Mend\FileSystem\File;
use \Mend\Logging\Logger;
use \Mend\Logging\ConsoleLogWriter;
use \Mend\Metrics\Report\Project;
use \Mend\Metrics\Synthesize\ReportBuilder;
use \Mend\Metrics\Synthesize\ReportWriterFactory;
use \Mend\Metrics\Synthesize\ReportSerializerJson;

// Possible output formats
const OUTPUT_FORMAT_TEXT = 'text';
const OUTPUT_FORMAT_JSON = 'json';

// Create default options
$options = new stdClass();
$options->memoryLimit = '1G';
$options->outputFormat = OUTPUT_FORMAT_TEXT;
$options->path = null;
$options->project = null;
$options->verbose = false;

// Script argument options
$params  = '';
$params .= 'm:'; // Memory limit
$params .= 'o:'; // Output format
$params .= 'p:'; // Project key
$params .= 'v';  // Verbosity flag
$params .= 'h';  // Help

// Read options
$opts = getopt( $params );

// Read path for analysis
$pathArg = end( $argv );

if ( $pathArg != $argv[ 0 ] ) {
	$options->path = realpath( $pathArg );
}
else {
	$options->path = null;
}

foreach ( $opts as $opt => $val ) {
	switch ( $opt ) {
		case 'h':
			help( $argv );
			exit( 0 );
		case 'm':
			$options->memoryLimit = $val;
			break;
		case 'o':
			$options->outputFormat = $val;
			break;
		case 'p':
			$options->project = $val;
			break;
		case 'v':
			$options->verbose = true;
			break;
	}
}

validateOptions( $options );
analyze( $options );

// --------------------------------------------

/* Validates options */
function validateOptions( $options ) {
	// validate project
	if ( $options->project == null ) {
		err( "No project specified." );
		exit( 1 );
	}

	// validate output format
	if ( !in_array( $options->outputFormat, array( OUTPUT_FORMAT_TEXT, OUTPUT_FORMAT_JSON ) ) ) {
		err( "Invalid output format: <{$options->outputFormat}>." );
		exit( 1 );
	}

	// validate memory limit
	if ( preg_match( '/^\d+[MG]$/', $options->memoryLimit ) == 0 ) {
		err( "Invalid memory specification: <{$options->memoryLimit}>" );
		exit( 1 );
	}

	// validate path
	if ( $options->path == null ) {
		err( "No path given to analyze." );
		exit( 1 );
	}

	if ( !is_readable( $options->path ) ) {
		err( "Specified path is not readable: <{$options->path}>." );
		exit( 1 );
	}
};

/* Analyze */
function analyze( $options ) {
	ini_set( 'memory_limit', $options->memoryLimit );

	if ( $options->verbose ) {
		Logger::setWriter( new ConsoleLogWriter( STDERR ) );
	}

	$project = new Project( $options->project );
	$report = null;

	if ( is_dir( $options->path ) ) {
		$directory = new Directory( $options->path );
		$report = ReportBuilder::analyzeDirectory( $project, $directory );
	}
	else if ( is_file( $options->path ) ) {
		$file = new File( $options->path );
		$report = ReportBuilder::analyzeFile( $project, $file );
	}
	else {
		err( "Path is neither a file or directory: <{$options->path}>." );
		exit( 1 );
	}

	if ( $options->outputFormat == OUTPUT_FORMAT_JSON ) {
		Logger::info( "Serializing report as JSON..." );

		$serializer = new ReportSerializerJson();
		$output = $serializer->serialize( $report );
	}
	else {
		Logger::info( "Writing report as {$options->outputType}..." );

		$writer = ReportWriterFactory::createWriterByName( $options->outputFormat );
		$output = $writer->write( $report );
	}

	Logger::info( "Done." );

	out( $output );
	exit( 0 );
}

/* Help output */
function help( array $argv ) {
	$script = basename( $argv[ 0 ] );

	$help = <<<HELP
usage: {$script} [options] [location]

Required:
    -p         Specify a unique project name.

Location:
    Specify a file or directory to analyze (required).

Options:
    -h         Prints this Help
    -m         Specify memory limit (default: 1G)
    -o         Specify output type: [text|json] (default: text)
    -v         Outputs verbose messages.

Example usage:
    {$script} .         Analyzes the current directory recursively.
    {$script} file.php  Analyzes the specified file.
HELP;
	out( $help );
};

/* Write to std out. */
function out( $msg ) {
	file_put_contents( 'php://stdout', $msg . PHP_EOL );
};

/* Write to std error. */
function err( $msg ) {
	file_put_contents( 'php://stderr', "ERROR {$msg}" . PHP_EOL );
};