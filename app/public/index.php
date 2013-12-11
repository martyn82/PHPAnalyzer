<?php

require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

if ( !defined( 'APP_DIR' ) ) {
	define( 'APP_DIR', realpath( __DIR__ . "/../../app" ) );
}

Autoloader::registerNamespace( "Controller", APP_DIR . "/controllers" );
Autoloader::registerNamespace( "MVC", APP_DIR . "/mvc" );
Autoloader::enable();

$viewScriptsPath = realpath( APP_DIR . "/views" );
$layoutViewScript = realpath( APP_DIR . "/views/layout" ) . "/default.phtml";

$indexController = new \Controller\IndexController( $viewScriptsPath, $layoutViewScript );
$indexController->dispatch( 'index' );
