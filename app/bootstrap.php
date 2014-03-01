<?php
require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

define( 'APP_DIR', realpath( __DIR__ . "/../app" ) );

chdir( APP_DIR );

$autoLoader = new Autoloader();
$autoLoader->addNamespace( "Application", APP_DIR );
$autoLoader->addNamespace( "Controller", APP_DIR . "/controllers" );
$autoLoader->register();
