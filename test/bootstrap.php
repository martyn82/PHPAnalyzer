<?php
require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

define( 'TEST_DIR', realpath( __DIR__ . "/test" ) );

require_once __DIR__ . "/TestCase.php";

if ( !isset( $autoLoader ) ) {
	$autoLoader = new Autoloader();
	$autoLoader->addNamespace( "Mend", LIB_DIR . "/Mend" );
	$autoLoader->register();
}

$autoLoader->addNamespace( "Mend", TEST_DIR . "/lib/Mend" );
