#!/usr/bin/php
<?php
require_once realpath( __DIR__ . "/cli" ) . "/bootstrap.php";

use Mend\Cli\Analyzer;
use Mend\Cli\Status;

$DEFAULT_TEMPLATE = realpath( __DIR__ . '/cli/report/template' ) . '/console.txt';

$options = getopt( Analyzer::getOptions() );
$options[ Analyzer::CURRENT_SCRIPT ] = basename( $argv[ 0 ] );

try {
	$analyzer = new Analyzer( $options, $mapVariables );
	$settings = $analyzer->getSettings();

	if ( $settings->getTemplate() == null ) {
		$analyzer->getSettings()->setTemplate( $DEFAULT_TEMPLATE );
	}

	$result = $analyzer->run();

	stop( $result->getMessage(), $result->getStatus() );
}
catch ( \Exception $e ) {
	if ( !empty( $analyzer ) && $analyzer->getSettings()->getVerbose() ) {
		stop( "ERROR: " . $e->__toString(), Status::STATUS_ERROR_GENERAL );
	}

	stop( "ERROR: " . $e->getMessage(), Status::STATUS_ERROR_GENERAL );
}
