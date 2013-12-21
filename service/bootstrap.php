<?php
date_default_timezone_set( 'Europe/Amsterdam' );

define( 'APPLICATION_ENV', getenv( 'APPLICATION_ENV' ) ? : 'production' );

if ( APPLICATION_ENV == 'development' ) {
	ini_set( 'display_startup_errors', 1 );
	ini_set( 'display_errors', '1' );
}

error_reporting( -1 );
set_error_handler(
	function ( $code, $message, $file = null, $line = null ) {
		throw new \ErrorException( $message, $code, $code, $file, $line );
	}
);

if ( !defined( 'ROOT_DIR' ) ) {
	define( 'ROOT_DIR', realpath( __DIR__ ) );
}

if ( !defined( 'LIB_DIR' ) ) {
	define( 'LIB_DIR', realpath( __DIR__ . "/../lib" ) );
}
