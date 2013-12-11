#!/usr/bin/php
<?php
require realpath( __DIR__ . "/app" ) . "/bootstrap.php";

use \Mend\FileSystem\Directory;
use \Mend\FileSystem\DirectoryArray;
use \Mend\FileSystem\File;
use \Mend\Logging\Logger;
use \Mend\Logging\ConsoleLogWriter;
use \Mend\Metrics\Report\Project;
use \Mend\Metrics\Synthesize\ReportBuilder;
use \Mend\Metrics\Synthesize\ReportWriterFactory;
use \Mend\Metrics\Synthesize\ReportSerializerJson;

// Possible output formats
const OUTPUT_FORMAT_NONE = '';
const OUTPUT_FORMAT_TEXT = 'text';
const OUTPUT_FORMAT_JSON = 'json';

// Create default options
$options = new stdClass();
$options->configFile = null;
$options->excludes = array();
$options->includes = array();
$options->memoryLimit = '1G';
$options->outputFormat = OUTPUT_FORMAT_NONE;
$options->path = null;
$options->project = new \stdClass();
$options->project->key = uniqid( 'proj_' );
$options->project->name = $options->project->key;
$options->project->path = null;
$options->savePath = realpath( __DIR__ ) . DIRECTORY_SEPARATOR . ".analysis";
$options->verbose = false;

// Script argument options
$params  = '';
$params .= 'c:'; // Configuration file
$params .= 'm:'; // Memory limit
$params .= 'o:'; // Output format
$params .= 'p:'; // Project key
$params .= 'v';  // Verbosity flag
$params .= 'h';  // Help

// Read options
$opts = getopt( $params );

foreach ( $opts as $opt => $val ) {
	switch ( $opt ) {
		case 'h':
			help( $argv );
			exit( 0 );
		case 'c':
			$options->configFile = $val;
			break;
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

// validate configuration file
if ( $options->configFile != null ) {
	if ( !is_file( $options->configFile ) || !is_readable( $options->configFile ) ) {
		err( "Configuration file must be a readable file: <{$options->configFile}>." );
		exit( 1 );
	}

	$options = readConfig( $options );
}
else {
	$path = realpath( end( $argv ) );

	if ( !empty( $path ) && $path != realpath( $argv[ 0 ] ) ) {
		$options->path = $path;
	}
}

validateOptions( $options );
analyze( $options );

// --------------------------------------------

/* Reads configuration */
function readConfig( $options ) {
	$config = parse_ini_file( $options->configFile, true );

	if ( !isset( $config[ 'project' ] ) ) {
		err( "No project section found in config." );
		exit( 1 );
	}

	if ( empty( $config[ 'project' ][ 'key' ] ) ) {
		err( "Project must have a unique key." );
		exit( 1 );
	}

	$options->project->key = $config[ 'project' ][ 'key' ];
	$options->project->name = !empty( $config[ 'project' ][ 'name' ] )
		? $config[ 'project' ][ 'name' ]
		: $config[ 'project' ][ 'key' ];
	$options->project->path = realpath( dirname( $options->configFile ) );

	if ( !empty( $config[ 'includes' ] ) ) {
		$options->includes = array_map(
			function ( $incPath ) {
				$realPath = realpath( $incPath );

				if ( empty( $realPath ) ) {
					return realpath( __DIR__ . DIRECTORY_SEPARATOR . $incPath );
				}

				return $realPath;
			},
			$config[ 'includes' ][ 'path' ]
		);
	}

	return $options;
};

/* Validates options */
function validateOptions( $options ) {
	// validate project
	if ( $options->project == null ) {
		err( "No project key specified." );
		exit( 1 );
	}

	// validate output format
	if (
		$options->outputFormat != null
		&& !in_array( $options->outputFormat, array( OUTPUT_FORMAT_TEXT, OUTPUT_FORMAT_JSON ) )
	) {
		err( "Invalid output format: <{$options->outputFormat}>." );
		exit( 1 );
	}

	// validate memory limit
	if ( preg_match( '/^\d+[MG]$/', $options->memoryLimit ) == 0 ) {
		err( "Invalid memory specification: <{$options->memoryLimit}>" );
		exit( 1 );
	}

	// validate path
	if ( empty( $options->includes ) ) {
		if ( $options->path == null ) {
			err( "No path given to analyze." );
			exit( 1 );
		}

		if ( !is_readable( $options->path ) ) {
			err( "Specified path is not readable: <{$options->path}>." );
			exit( 1 );
		}
	}

	// special case
	if ( $options->outputFormat == OUTPUT_FORMAT_NONE ) {
		out( "Start analysis..." );
	}
};

/* Analyze */
function analyze( $options ) {
	ini_set( 'memory_limit', $options->memoryLimit );

	if ( $options->verbose ) {
		Logger::setWriter( new ConsoleLogWriter( STDERR ) );
	}

	if ( !is_dir( $options->savePath ) ) {
		Logger::info( "Creating analysis directory at <{$options->savePath}>..." );

		if ( !mkdir( $options->savePath ) ) {
			err( "Failed to create save path." );
			exit( 1 );
		}
	}

	$project = new Project( $options->project->key, $options->project->name, $options->project->path );
	$report = null;

	if ( $options->path == null ) {
		$directories = new DirectoryArray();

		foreach ( $options->includes as $path ) {
			if ( !is_dir( $path ) ) {
				err( "The given include path is no directory: <{$path}>." );
				continue;
			}

			$directories[] = new Directory( $path );
		}

		$report = ReportBuilder::analyzeDirectories( $project, $directories );
	}
	else {
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
	}

	if ( $options->outputFormat == OUTPUT_FORMAT_NONE ) {
		Logger::info( "Serializing report as JSON..." );
		$serializer = new ReportSerializerJson();
		$serialized = $serializer->serialize( $report );

		Logger::info( "Writing to folder..." );
		file_put_contents( $options->savePath . DIRECTORY_SEPARATOR . date( 'Ymd_His' ) . '.json', $serialized );

		$output = "Analysis done.";
	}
	else if ( $options->outputFormat == OUTPUT_FORMAT_JSON ) {
		Logger::info( "Serializing report as JSON..." );

		$serializer = new ReportSerializerJson();
		$output = $serializer->serialize( $report );
	}
	else {
		Logger::info( "Writing report as {$options->outputFormat}..." );

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
usage: {$script} [options] -- [location]

Location:
    Specify a file or directory to analyze (required).

Options:
    -h         Prints this Help
    -m         Specify memory limit (default: 1G)
    -p         Specify a unique project name.
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