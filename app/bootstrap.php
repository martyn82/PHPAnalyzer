<?php
date_default_timezone_set( 'Europe/Amsterdam' );

error_reporting( -1 );
ini_set( 'display_errors', '1' );

if ( !defined( 'ROOT_DIR' ) ) {
	define( 'ROOT_DIR', realpath( __DIR__ ) );
}

if ( !defined( 'LIB_DIR' ) ) {
	define( 'LIB_DIR', realpath( __DIR__ . "/../lib" ) );
}

require_once ROOT_DIR . "/../vendor/nikic/php-parser/lib/bootstrap.php";
require_once LIB_DIR . "/Autoloader.php";

Autoloader::registerNamespace( "Mend", LIB_DIR . "/Mend" );
Autoloader::enable();

\Mend\Logging\Logger::setWriter( new \Mend\Logging\NullLogWriter() );