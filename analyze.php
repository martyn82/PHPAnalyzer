#!/usr/bin/php
<?php
require realpath( __DIR__ . "/app" ) . "/bootstrap.php";

use \Mend\FileSystem\Directory;
use \Mend\FileSystem\File;
use \Mend\Logging\Logger;
use \Mend\Logging\ConsoleLogWriter;
use \Mend\Metrics\Synthesize\ReportBuilder;
use \Mend\Metrics\Synthesize\ReportWriterFactory;
use \Mend\Metrics\Synthesize\ReportSerializerJson;

const OUTPUT_TYPE_TEXT = 'text';
const OUTPUT_TYPE_JSON = 'json';
$validOutputs = array( OUTPUT_TYPE_TEXT, OUTPUT_TYPE_JSON );

// Create default options
$options = new stdClass();
$options->outputType = OUTPUT_TYPE_TEXT;
$options->memoryLimit = '1G';
$options->path = getcwd();
$options->verbose = false;

// Iterate through script arguments
for ( $index = 0; $index < count( $argv ); $index++ ) {
	if ( $index == 0 ) {
		continue;
	}

	$arg = $argv[ $index ];

	switch ( $arg ) {
		case '--':
			// ignore
			break;

		case '-h':
			help( $argv );
			exit( 0 );

		case '-m':
			$index++;
			$options->memoryLimit = $argv[ $index ] ?: $options->memoryLimit;
			break;

		case '-o':
			$index++;

			if ( !in_array( $argv[ $index ], array( OUTPUT_TYPE_TEXT, OUTPUT_TYPE_JSON ) ) ) {
				err( "'{$argv[ $index ]}' is an invalid output type." );
				exit( 1 );
			}

			$options->outputType = $argv[ $index ];
			break;

		case '-v':
			$options->verbose = true;
			break;

		default:
			if ( count( $argv ) == $index + 1 ) {
				$path = realpath( $argv[ $index ] );

				if ( empty( $path ) ) {
					err( "Invalid option: {$arg}" );
					exit( 1 );
				}

				$options->path = $path;
			}
			break;
	}
}

ini_set( 'memory_limit', $options->memoryLimit );

if ( $options->path == null ) {
	err( "No path given to analyze." );
	exit( 1 );
}

if ( !is_readable( $options->path ) ) {
	err( "Specified path is not readable: <{$options->path}>." );
	exit( 1 );
}

if ( $options->verbose ) {
	Logger::setWriter( new ConsoleLogWriter( STDERR ) );
}

analyze( $options );

// --------------------------------------------

/* Analyze */
function analyze( $options ) {
	$report = null;

	if ( is_dir( $options->path ) ) {
		$directory = new Directory( $options->path );
		$report = ReportBuilder::analyzeDirectory( $directory );
	}
	else if ( is_file( $options->path ) ) {
		$file = new File( $options->path );
		$report = ReportBuilder::analyzeFile( $file );
	}
	else {
		err( "Path is neither a file or directory: <{$options->path}>." );
		exit( 1 );
	}

	if ( $options->outputType == OUTPUT_TYPE_JSON ) {
		$serializer = new ReportSerializerJson();
		$output = $serializer->serialize( $report );
	}
	else {
		$writer = ReportWriterFactory::createWriterByName( $options->outputType );
		$output = $writer->write( $report );
	}

	out( $output );
	exit( 0 );
}

/* Help output */
function help( array $argv ) {
	$script = basename( $argv[ 0 ] );

	$help = <<<HELP
usage: {$script} [options] [location]

Options:
    -h         Prints this Help
    -o         Specify output type: [text|json] default: text.
    -v         Outputs verbose messages.

Location:
    Specify a file or directory to analyze.

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