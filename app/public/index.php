<?php

require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

if ( !defined( 'APP_DIR' ) ) {
	define( 'APP_DIR', realpath( __DIR__ . "/../../app" ) );
}

Autoloader::registerNamespace( "Controller", APP_DIR . "/controllers" );
Autoloader::registerNamespace( "MVC", APP_DIR . "/mvc" );
Autoloader::enable();

$viewScriptsPath = realpath( APP_DIR . "/views" );

$indexController = new \Controller\IndexController( $viewScriptsPath );
$indexController->dispatch( 'index' );
