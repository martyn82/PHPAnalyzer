<?php
use Mend\Mvc\ViewRenderer;
use Mend\Mvc\ViewRendererOptions;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Mvc\Controller\FrontController;
use Mend\Mvc\Controller\ControllerLoader;
use Mend\Mvc\Layout;

require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

if ( !defined( 'APP_DIR' ) ) {
	define( 'APP_DIR', realpath( __DIR__ . "/../../app" ) );
}

$autoLoader = new Autoloader();
$autoLoader->addNamespace( "Controller", APP_DIR . "/controllers" );
$autoLoader->addNamespace( "MVC", APP_DIR . "/mvc" );
$autoLoader->register();

$viewScriptsPath = realpath( APP_DIR . "/views" );
$layoutViewScript = realpath( APP_DIR . "/views/layout" );

$request = WebRequest::createFromGlobals();
$response = new WebResponse( $request->getUrl() );

$options = new ViewRendererOptions();
$options->setViewTemplatePath( $viewScriptsPath );
$options->setViewTemplateSuffix( '.phtml' );
$options->setLayoutTemplatePath( $layoutViewScript );
$options->setLayoutDefaultTemplate( 'default' );
$options->setLayoutTemplateSuffix( '.phtml' );

$renderer = new ViewRenderer( $options );
$loader = new ControllerLoader( array( 'Controller' ) );

$frontController = new FrontController( $request, $response, $renderer, $loader );
$frontController->setLayout( new Layout() );
$frontController->dispatchRequest();
$frontController->sendResponse();
