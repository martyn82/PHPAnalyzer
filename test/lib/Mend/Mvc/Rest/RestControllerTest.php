<?php
namespace Mend\Mvc\Rest;

use Mend\Collections\Map;
use Mend\Mvc\Controller;
use Mend\Network\Web\Url;
use Mend\Network\Web\HttpMethod;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

class RestControllerTest extends \TestCase {
	public function setUp() {
		self::markTestSkipped();
	}

	/**
	 * @dataProvider urlProvider
	 *
	 * @param string $urlString
	 * @param string $method
	 * @param string $expectedActionMethod
	 */
	public function testDispatch( $urlString, $method, $expectedActionMethod ) {
		$request = $this->createRequest( $urlString, $method );
		$response = $this->createResponse();
		$factory = $this->createFactory();

		$resourceController = $this->getMock(
			'\Mend\Mvc\Rest\ResourceController',
			array( 'actionIndex', 'actionRead', 'actionCreate', 'actionUpdate', 'actionDelete', 'getResponse', 'getActionName', 'getControllerName' ),
			array( $request, $response, $factory )
		);

		$resourceController->expects( self::any() )
			->method( 'getResponse' )
			->will( self::returnValue( $response ) );

		$resourceController->expects( self::once() )
			->method( $expectedActionMethod );

		$controller = new DummyRestController( $request, $response, $factory );
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

		$request = $this->createRequest( $urlString, $method );
		$response = $this->createResponse();
		$factory = $this->createFactory();

		$resourceController = $this->getMock(
			'\Mend\Mvc\Rest\ResourceController',
			array( 'actionIndex', 'actionRead', 'actionCreate', 'actionUpdate', 'actionDelete', 'getResponse', 'getActionName', 'getControllerName' ),
			array( $request, $response, $factory )
		);

		$resourceController->expects( self::any() )
			->method( 'getResponse' )
			->will( self::returnValue( $response ) );

		$controller = new DummyRestController( $request, $response, $factory );
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
		$response = $this->getMock( '\Mend\Network\Web\WebResponse', array( 'getHeaders' ), array(), '', false );

		$response->expects( self::any() )
			->method( 'getHeaders' )
			->will( self::returnValue( new Map() ) );

		return $response;
	}

	private function createFactory() {
		return $this->getMock( '\Mend\Mvc\ControllerFactory', array(), array(), '', false );
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
