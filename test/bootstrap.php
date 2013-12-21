<?php
if ( !defined( 'LIB_DIR' ) ) {
	define( 'LIB_DIR', realpath( __DIR__ . "/../lib" ) );
}

if ( !defined( 'TEST_DIR' ) ) {
	define( 'TEST_DIR', __DIR__ );
}

require_once __DIR__ . "/TestCase.php";
require_once LIB_DIR . "/Autoloader.php";

$autoLoader = new Autoloader();
$autoLoader->addNamespace( "Mend", LIB_DIR . "/Mend" );
$autoLoader->addNamespace( "Mend", TEST_DIR . "/lib/Mend" );
$autoLoader->register();