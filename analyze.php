#!/usr/bin/php
<?php
require_once realpath( __DIR__ . "/cli" ) . "/bootstrap.php";

use Mend\Cli\Analyzer;
use Mend\Cli\Options;
use Mend\Cli\Status;
use Mend\Logging\Logger;
use Mend\Logging\Aspect\LogAspect;
use Mend\Logging\StreamHandler;
use Mend\IO\Stream\FileStreamWriter;
use Mend\IO\Stream\HandleStreamWriter;
use Mend\IO\Stream\NullStreamWriter;
use Mend\Logging\LogLevel;

$DEFAULT_TEMPLATE = realpath( __DIR__ . '/cli/report/template' ) . '/console.txt';

$options = getopt( Analyzer::getOptions() );
$options[ Analyzer::CURRENT_SCRIPT ] = basename( $argv[ 0 ] );

define( 'VERBOSE', isset( $options[ Options::OPT_VERBOSITY_FLAG ] ) );

if ( VERBOSE ) {
	$verboseHandler = new StreamHandler( new HandleStreamWriter( STDERR ) );
}
else {
	$verboseHandler = new StreamHandler( new NullStreamWriter() );
}

Logger::registerHandler(
	$verboseHandler,
	array( LogLevel::LEVEL_DEBUG, LogLevel::LEVEL_INFO )
);

Logger::registerHandler(
	new StreamHandler( new HandleStreamWriter( STDERR ) ),
	array_diff(
		LogLevel::getLevels(),
		array( LogLevel::LEVEL_DEBUG, LogLevel::LEVEL_INFO )
	)
);

$analyzer = new Analyzer( $options, $mapVariables );
$settings = $analyzer->getSettings();

if ( $settings->getTemplate() == null ) {
	$analyzer->getSettings()->setTemplate( $DEFAULT_TEMPLATE );
}

$result = $analyzer->run();

stop( $result->getMessage(), $result->getStatus() );
