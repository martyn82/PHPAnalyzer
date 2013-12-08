<?php
date_default_timezone_set( 'Europe/Amsterdam' );

if ( !defined( 'ROOT_DIR' ) ) {
	define( 'ROOT_DIR', realpath( __DIR__ ) );
}

if ( !defined( 'LIB_DIR' ) ) {
	define( 'LIB_DIR', realpath( __DIR__ . "/../lib" ) );
}

require_once ROOT_DIR . "/../vendor/nikic/php-parser/lib/bootstrap.php";
require_once LIB_DIR . "/Autoloader.php";

Autoloader::registerNamespace( "FileSystem", LIB_DIR . "/FileSystem" );
Autoloader::registerNamespace( "Metrics", LIB_DIR . "/Metrics" );
Autoloader::registerNamespace( "Parser", LIB_DIR . "/Parser" );
Autoloader::registerNamespace( "Logging", LIB_DIR . "/Logging" );

Autoloader::enable();

\Logging\Logger::setWriter( new \Logging\NullLogWriter() );