<?php
require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";
require_once __DIR__ . "/config/var-mapping.php";

use Mend\Cli\Status;
use Mend\Logging\Logger;

/**
 * Stops the script with given message and status code.
 *
 * @param string $message
 * @param integer $status
 */
function stop( $message, $status = Status::STATUS_OK ) {
	$handle = STDOUT;

	if ( $status != Status::STATUS_OK ) {
		$handle = STDERR;
	}

	fwrite( $handle, $message . PHP_EOL );
	exit( $status );
}

/**
 * Writes to stdout.
 *
 * @param string $message
 */
function out( $message ) {
	fwrite( STDOUT, $message . PHP_EOL );
}

/**
 * Writes to stderr.
 *
 * @param string $message
 */
function error( $message ) {
	fwrite( STDERR, $message . PHP_EOL );
}

/**
 * Logs time statistics.
 *
 * @param integer $startTime
 */
function logTimeStats( $startTime ) {
	$diff = time() - $startTime;
	$timeSuffix = 'seconds';

	if ( $diff > 60 ) {
		$diff /= 60;
		$timeSuffix = 'minutes';
	}

	if ( $diff > 60 ) {
		$diff /= 60;
		$timeSuffix = 'hours';
	}

	$diff = round( $diff, 2 );
	Logger::debug( "Time: {$diff} {$timeSuffix}" );
}

/**
 * Logs memory usage statistics.
 */
function logMemoryStats() {
	$memoryUsage = memory_get_peak_usage();
	$memorySuffix = 'B';
	$divided = 0;

	while ( $memoryUsage > 1024 ) {
		$memoryUsage /= 1024;
		$divided++;

		if ( $divided == 4 ) {
			break;
		}
	}

	switch ( $divided ) {
		case 1:
			$memorySuffix = 'kB';
			break;

		case 2:
			$memorySuffix = 'MB';
			break;

		case 3:
			$memorySuffix = 'GB';
			break;

		case 4:
			$memorySuffix = 'TB';
			break;
	}

	$memoryUsage = round( $memoryUsage, 2 );
	Logger::debug( "Memory usage: {$memoryUsage} {$memorySuffix}" );
}

set_exception_handler( function ( \Exception $e ) {
	if ( defined( 'VERBOSE' ) && VERBOSE ) {
		stop( "ERROR: " . $e->__toString(), Status::STATUS_ERROR_GENERAL );
	}

	stop( "ERROR: " . $e->getMessage(), Status::STATUS_ERROR_GENERAL );
} );