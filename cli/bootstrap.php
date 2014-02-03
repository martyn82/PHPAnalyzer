<?php
require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";
require_once __DIR__ . "/config/var-mapping.php";

use Mend\Cli\Status;

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

set_exception_handler( function ( \Exception $e ) {
	if ( defined( 'VERBOSE' ) && VERBOSE ) {
		stop( "ERROR: " . $e->__toString(), Status::STATUS_ERROR_GENERAL );
	}

	stop( "ERROR: " . $e->getMessage(), Status::STATUS_ERROR_GENERAL );
} );