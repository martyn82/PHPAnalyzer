<?php
use Mend\Network\Web\HttpRequest;
use Mend\Network\Web\RESTServer;

require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

if ( !defined( 'APP_DIR' ) ) {
	define( 'APP_DIR', realpath( __DIR__ . "/../../service" ) );
}

$request = parse_url( $_SERVER[ 'REQUEST_URI' ] );
$path = $request[ 'path' ];
$parameters = array_filter( explode( '/', $path ) );

$resource = array_shift( $parameters );
$identifier = array_shift( $parameters );

$properties = array();
while ( count( $parameters ) > 0 ) {
	$properties[] = array_shift( $parameters );
}

$_GET[ 'id' ] = $identifier;
$_GET[ 'resource' ] = $resource;
$_GET[ 'properties' ] = $properties;

require_once LIB_DIR . "/Autoloader.php";

$autoLoader = new Autoloader();
$autoLoader->addNamespace( "Mend", LIB_DIR . "/Mend" );
$autoLoader->addNamespace( "rest", APP_DIR . "/rest" );
$autoLoader->addNamespace( "resource", APP_DIR . "/resources" );
$autoLoader->register();

$request = HttpRequest::create();
$server = new RESTServer( $request );

$server->dispatch( $resource );