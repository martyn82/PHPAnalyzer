<?php
namespace Mend\Mvc\Rest;

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
	public function testDispatch( $urlString, $method, $expectedActionMethod ) {
		ControllerClassExists::$classExistsResult = true;

		$request = $this->createRequest( $urlString, $method );
		$response = $this->createResponse();
		$renderer = $this->createViewRenderer();
		$loader = $this->createLoader();

		$resourceController = $this->getMock(
			'\Mend\Mvc\Rest\ResourceController',
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

	/**
	 * @expectedException \Mend\Mvc\Controller\ControllerException
	 */
	public function testDispatchInvalidRequestMethod() {
		$urlString = 'http://www.example.org';
		$method = 'non';

		ControllerClassExists::$classExistsResult = true;

		$request = $this->createRequest( $urlString, $method );
		$response = $this->createResponse();
		$renderer = $this->createViewRenderer();
		$loader = $this->createLoader();

		$resourceController = $this->getMock(
			'\Mend\Mvc\Rest\ResourceController',
			array( 'actionIndex', 'actionRead', 'actionCreate', 'actionUpdate', 'actionDelete' ),
			array( $request, $response, $renderer )
		);

		$controller = new DummyRestController( $request, $response, $renderer, $loader );
		$controller->setController( $resourceController );

		$controller->dispatchRequest();

		self::fail( 'Test should have triggered an exception.' );
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
		return $this->getMock( '\Mend\Mvc\View\ViewRenderer', array(), array(), '', false );
	}

	private function createLoader() {
		return $this->getMock( '\Mend\Mvc\Controller\ControllerLoader', array(), array(), '', false );
	}
}

class DummyRestController extends RestController {
	/**
	 * @var ResourceController
	 */
	private $controller;

	/**
	 * Sets the controller to use.
	 *
	 * @param ResourceController $controller
	 */
	public function setController( ResourceController $controller ) {
		$this->controller = $controller;
	}

	/**
	 * @see FrontController::createController()
	 */
	protected function createController( $controllerName ) {
		return $this->controller;
	}
}
