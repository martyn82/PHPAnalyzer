<?php
require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

ini_set( 'memory_limit', '1G' );

define( 'APP_DIR', realpath( __DIR__ . "/../service" ) );

chdir( APP_DIR );

$autoLoader = new Autoloader();

$autoLoader->addNamespace( "Mend", LIB_DIR . "/Mend" );
$autoLoader->addNamespace( "Controller", APP_DIR . "/controllers" );
$autoLoader->addNamespace( "Model", APP_DIR . "/model" );

$autoLoader->register();
