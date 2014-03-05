<?php
require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

define( 'APP_DIR', realpath( __DIR__ . "/../service" ) );

chdir( APP_DIR );

$autoLoader = new Autoloader();
$autoLoader->addNamespace( "Mend", LIB_DIR . "/Mend" );
$autoLoader->addNamespace( "Controller", APP_DIR . "/controllers" );
$autoLoader->register();
