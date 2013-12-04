<?php
if ( !defined( 'SRC_DIR' ) ) {
	define( 'SRC_DIR', realpath( __DIR__ . "/../src" ) );
}

if ( !defined( 'TEST_DIR' ) ) {
	define( 'TEST_DIR', realpath( __DIR__ . "/tests" ) );
}

require_once SRC_DIR . "/Autoloader.php";
require_once __DIR__ . "/TestCase.php";

Autoloader::setRootDir( SRC_DIR );
Autoloader::register();