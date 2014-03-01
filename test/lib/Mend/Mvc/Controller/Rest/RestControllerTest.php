<?php
namespace Mend\Mvc\Controller\Rest;

use Mend\Collections\Map;
use Mend\Mvc\Controller;
use Mend\Mvc\ViewRenderer;
use Mend\Mvc\ViewRendererOptions;
use Mend\Network\Web\Url;
use Mend\Network\Web\HttpMethod;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

require_once "ControllerClassExists.php";

class RestControllerTest extends \TestCase {
	public function setUp() {
		ControllerClassExists::$classExistsResult = null;
	}

	public function tearDown() {
		ControllerClassExists::$classExistsResult = null;
	}

	/**
	 * @dataProvider urlProvider
	 *
	 * @param string $urlString
	 * @param string $method
	 * @param string $expectedActionMethod
	 */
	public function test( $urlString, $method, $expectedActionMethod ) {
		ControllerClassExists::$classExistsResult = true;

		$request = $this->createRequest( $urlString, $method );
		$response = $this->createResponse();
		$renderer = $this->createViewRenderer();
		$loader = $this->createLoader();

		$resourceController = $this->getMock(
			'\Mend\Mvc\Controller',
			array( 'actionIndex', 'actionRead', 'actionCreate', 'actionUpdate', 'actionDelete' ),
			array( $request, $response, $renderer )
		);

		$resourceController->expects( self::once() )
			->method( $expectedActionMethod );

		$controller = new DummyRestController( $request, $response, $renderer, $loader );
		$controller->setController( $resourceController );

		$controller->dispatchRequest();
	}

	public function urlProvider() {
		return array(
			array( 'http://www.example.org/resource'  , HttpMethod::METHOD_GET   , 'actionIndex'  ),
			array( 'http://www.example.org/resource/1', HttpMethod::METHOD_GET   , 'actionRead'   ),
			array( 'http://www.example.org/resource'  , HttpMethod::METHOD_POST  , 'actionCreate' ),
			array( 'http://www.example.org/resource/1', HttpMethod::METHOD_PUT   , 'actionUpdate' ),
			array( 'http://www.example.org/resource/1', HttpMethod::METHOD_DELETE, 'actionDelete' ),
			array( 'http://www.example.org/resource/1/sub', HttpMethod::METHOD_GET, 'actionRead' )
		);
	}

	private function createRequest( $urlString, $method ) {
		$url = Url::createFromString( $urlString );
		$request = $this->getMock( '\Mend\Network\Web\WebRequest', array( 'getUrl' ), array( $url, $method ) );

		$request->expects( self::any() )
			->method( 'getUrl' )
			->will( self::returnValue( $url ) );

		return $request;
	}

	private function createResponse() {
		return $this->getMock( '\Mend\Network\Web\WebResponse', array(), array(), '', false );
	}

	private function createViewRenderer() {
		return $this->getMock( '\Mend\Mvc\ViewRenderer', array(), array(), '', false );
	}

	private function createLoader() {
		return $this->getMock( '\Mend\Mvc\Controller\ControllerLoader', array(), array(), '', false );
	}
}

class DummyRestController extends RestController {
	/**
	 * @var Controller
	 */
	private $controller;

	/**
	 * Sets the controller to use.
	 *
	 * @param Controller $controller
	 */
	public function setController( Controller $controller ) {
		$this->controller = $controller;
	}

	/**
	 * @see FrontController::createController()
	 */
	protected function createController( $controllerName ) {
		return $this->controller;
	}
}

abstract class ResourceController extends Controller {
	public function actionIndex() { /* no-op */ }
	public function actionCreate() { /* no-op */ }
	public function actionUpdate() { /* no-op */ }
	public function actionDelete() { /* no-op */ }
	public function actionRead() { /* no-op */ }
}
