<?php
require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

define( 'TEST_DIR', \realpath( __DIR__ . "/test" ) );
define( 'SERVICE_DIR', \realpath( __DIR__ . "/../service" ) );

require_once __DIR__ . "/TestCase.php";
require_once __DIR__ . "/FileSystem.php";
require_once __DIR__ . "/FileSystemExt.php";

if ( !isset( $autoLoader ) ) {
	$autoLoader = new \Autoloader();
	$autoLoader->addNamespace( "Mend", LIB_DIR . "/Mend" );
	$autoLoader->register();
}

$autoLoader->addNamespace( "Controller", SERVICE_DIR . "/controllers" );
$autoLoader->addNamespace( "Model", SERVICE_DIR . "/model" );
