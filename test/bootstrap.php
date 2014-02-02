<?php
if ( !defined( 'ROOT_DIR' ) ) {
	define( 'ROOT_DIR', realpath( __DIR__ ) );
}

if ( !defined( 'LIB_DIR' ) ) {
	define( 'LIB_DIR', realpath( __DIR__ . "/../lib" ) );
}

if ( !defined( 'TEST_DIR' ) ) {
	define( 'TEST_DIR', __DIR__ );
}

define( 'PARSER_BOOTSTRAP', realpath( ROOT_DIR . '/../vendor/nikic/php-parser/lib' ) . '/bootstrap.php' );

date_default_timezone_set( 'Europe/Amsterdam' );

require_once __DIR__ . "/TestCase.php";
require_once LIB_DIR . "/Autoloader.php";

$autoLoader = new Autoloader();
$autoLoader->addNamespace( "Mend", LIB_DIR . "/Mend" );
$autoLoader->addNamespace( "Mend", TEST_DIR . "/lib/Mend" );
$autoLoader->register();