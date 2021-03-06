<?php
namespace Mend\Rest;

use Mend\Collections\Map;
use Mend\Mvc\Context;
use Mend\Mvc\Controller;
use Mend\Mvc\Controller\PageController;
use Mend\Mvc\View\ViewRenderer;
use Mend\Network\Web\HttpMethod;
use Mend\Network\Web\Url;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

class RestControllerTest extends \TestCase {
	/**
	 * @dataProvider requestProvider
	 *
	 * @param string $method
	 * @param string $urlString
	 * @param string $expectedAction
	 */
	public function testDispatch( $method, $urlString, $expectedAction ) {
		$url = Url::createFromString( $urlString );
		$request = $this->createRequest( $method, $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$controller = $this->createController( $request, $response, $renderer, $context );

		$actions = array(
			'actionIndex',
			'actionRead',
			'actionCreate',
			'actionUpdate',
			'actionDelete'
		);
		$actionMethod = 'action' . ucfirst( $expectedAction );

		foreach ( $actions as $action ) {
			$times = $action == $actionMethod ? self::once() : self::never();

			$controller->expects( $times )
				->method( $action );
		}

		$factory = $this->createFactory( $controller );
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$rest = new RestController( $request, $response, $factory, $renderer, $context );
		$rest->dispatchRequest();
	}

	public function requestProvider() {
		return array(
			array( HttpMethod::METHOD_GET, 'http://www.example.org/foo', RestAction::ACTION_INDEX ),
			array( HttpMethod::METHOD_GET, 'http://www.example.org/foo/1', RestAction::ACTION_READ ),
			array( HttpMethod::METHOD_GET, 'http://www.example.org/foo/1/bar', RestAction::ACTION_READ ),
			array( HttpMethod::METHOD_POST, 'http://www.example.org/foo', RestAction::ACTION_CREATE ),
			array( HttpMethod::METHOD_PUT, 'http://www.example.org/foo/101021', RestAction::ACTION_UPDATE ),
			array( HttpMethod::METHOD_PATCH, 'http://www.example.org/foo/101021', RestAction::ACTION_UPDATE ),
			array( HttpMethod::METHOD_DELETE, 'http://www.example.org/foo/131', RestAction::ACTION_DELETE )
		);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testDispatchInvalidMethod() {
		$this->testDispatch( HttpMethod::METHOD_OPTIONS, 'http://www.example.org/foo/1', null );
		self::fail( "Test should have triggered an exception." );
	}

	private function createContext() {
		return $this->getMockBuilder( '\Mend\Mvc\Context' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createViewRenderer() {
		return $this->getMockBuilder( '\Mend\Mvc\View\ViewRenderer' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createRequest( $method, Url $url ) {
		$request = $this->getMockBuilder( '\Mend\Network\Web\WebRequest' )
			->setMethods( array( 'getMethod', 'getUrl' ) )
			->setConstructorArgs( array( $url ) )
			->getMock();

		$request->expects( self::any() )
			->method( 'getUrl' )
			->will( self::returnValue( $url ) );

		$request->expects( self::any() )
			->method( 'getMethod' )
			->will( self::returnValue( $method ) );

		return $request;
	}

	private function createResponse( Url $url ) {
		$response = $this->getMockBuilder( '\Mend\Network\Web\WebResponse' )
			->setMethods( array( 'getHeaders' ) )
			->setConstructorArgs( array( $url ) )
			->getMock();

		$response->expects( self::any() )
			->method( 'getHeaders' )
			->will( self::returnValue( new Map() ) );

		return $response;
	}

	private function createFactory( Controller $controller = null ) {
		$factory = $this->getMockBuilder( '\Mend\Mvc\ControllerFactory' )
			->setMethods( array( 'createController' ) )
			->setConstructorArgs( array( array() ) )
			->getMock();

		$factory->expects( self::any() )
			->method( 'createController' )
			->will( self::returnValue( $controller ) );

		return $factory;
	}

	private function createController(
		WebRequest $request,
		WebResponse $response,
		ViewRenderer $renderer,
		Context $context
	) {
		$factory = $this->createFactory();
		$controller = $this->getMock(
			'\Mend\Rest\ResourceController',
			array( 'actionIndex', 'actionRead', 'actionCreate', 'actionUpdate', 'actionDelete', 'render' ),
			array( $request, $response, $factory, $renderer, $context )
		);

		return $controller;
	}
}
