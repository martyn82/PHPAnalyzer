<?php
if ( !defined( 'LIB_DIR' ) ) {
	define( 'LIB_DIR', realpath( __DIR__ . "/../lib" ) );
}

if ( !defined( 'TEST_DIR' ) ) {
	define( 'TEST_DIR', realpath( __DIR__ . "/tests" ) );
}

require_once LIB_DIR . "/Autoloader.php";
require_once __DIR__ . "/TestCase.php";
require_once __DIR__ . "/../vendor/nikic/php-parser/lib/bootstrap.php";

Autoloader::addIgnorePrefix( 'PHP' );
Autoloader::addIgnorePrefix( 'PHPParser' );

Autoloader::setRootDir( LIB_DIR );
Autoloader::register();