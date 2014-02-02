<?php
date_default_timezone_set( 'Europe/Amsterdam' );

define( 'APPLICATION_ENV', getenv( 'APPLICATION_ENV' ) ? : 'production' );
define( 'E_ALL_ERRORS', -1 );

ini_set( 'display_startup_errors', 1 );
ini_set( 'display_errors', 'stdout' );

error_reporting( E_ALL_ERRORS );

if ( !defined( 'ROOT_DIR' ) ) {
	define( 'ROOT_DIR', realpath( __DIR__ ) );
}

if ( !defined( 'LIB_DIR' ) ) {
	define( 'LIB_DIR', realpath( __DIR__ . "/lib" ) );
}

if ( !defined( 'CLI_DIR' ) ) {
	define( 'CLI_DIR', realpath( __DIR__ . "/cli" ) );
}

require_once ROOT_DIR . "/vendor/nikic/php-parser/lib/bootstrap.php";
require_once LIB_DIR . "/Autoloader.php";

$autoLoader = new Autoloader();
$autoLoader->addNamespace( "Mend", LIB_DIR . "/Mend" );
$autoLoader->register();
