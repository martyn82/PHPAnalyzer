#!/usr/bin/env php
<?php
$startTime = time();

require_once realpath( __DIR__ . "/cli" ) . "/bootstrap.php";

use Mend\Cli\Analyzer;
use Mend\Cli\Options;
use Mend\Cli\Status;
use Mend\Logging\Logger;
use Mend\Logging\LogLevel;
use Mend\Logging\StreamHandler;
use Mend\Logging\Aspect\LogAspect;
use Mend\IO\Stream\FileStreamWriter;
use Mend\IO\Stream\HandleStreamWriter;
use Mend\IO\Stream\NullStreamWriter;

// read options
$options = getopt( implode( '', Analyzer::getOptions() ) );
$options[ Analyzer::CURRENT_SCRIPT ] = basename( $argv[ 0 ] );

// set verbose flag value
define( 'VERBOSE', isset( $options[ Options::OPT_VERBOSITY_FLAG ] ) );
$verbosityLevels = array( LogLevel::LEVEL_DEBUG, LogLevel::LEVEL_INFO );
$verboseStream = VERBOSE
	? new HandleStreamWriter( STDERR )
	: new NullStreamWriter();

// initialize verbosity logger
Logger::registerHandler(
	new StreamHandler( $verboseStream ),
	$verbosityLevels
);

// initialize regular logger, logs all levels except the verbosity ones
Logger::registerHandler(
	new StreamHandler( new HandleStreamWriter( STDERR ) ),
	array_diff(
		LogLevel::getLevels(),
		$verbosityLevels
	)
);

// run the analysis
$analyzer = new Analyzer( $options, $mapVariables );
$settings = $analyzer->getSettings();

if ( $settings->getSummarize() ) {
	$settings->setTemplatePath( realpath( CLI_DIR . "/report/template" ) . "/console.txt" );
}

$result = $analyzer->run();

if ( $result->isError() ) {
	error( $result->getMessage() );
}
else {
	out( $result->getMessage() );
}

if ( VERBOSE ) {
	logTimeStats( $startTime );
	logMemoryStats();
}

exit( $result->getStatus() );
