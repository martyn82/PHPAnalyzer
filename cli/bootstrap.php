<?php
require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";
require_once __DIR__ . "/config/var-mapping.php";

function stopError( $message ) {
	fwrite( STDERR, 'ERROR: ' . $message . PHP_EOL );
	exit( 1 );
}

set_exception_handler( function ( \Exception $e ) {
	stopError( $e->__toString() );
} );