<?php
namespace Mend\Rest;

use Mend\Mvc\Controller;
use Mend\Mvc\Controller\PageController;
use Mend\Network\Web\HttpMethod;
use Mend\Network\Web\Url;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Mvc\View\ViewRenderer;

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

		$controller = $this->createController( $request, $response, $renderer );

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

		$rest = new RestController( $request, $response, $factory, $renderer );
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

	private function createViewRenderer() {
		return $this->getMock( '\Mend\Mvc\View\ViewRenderer', array(), array(), '', false );
	}

	private function createRequest( $method, Url $url ) {
		$request = $this->getMock( '\Mend\Network\Web\WebRequest', array( 'getMethod', 'getUrl' ), array( $url ) );

		$request->expects( self::any() )
			->method( 'getUrl' )
			->will( self::returnValue( $url ) );

		$request->expects( self::any() )
			->method( 'getMethod' )
			->will( self::returnValue( $method ) );

		return $request;
	}

	private function createResponse( Url $url ) {
		return $this->getMock( '\Mend\Network\Web\WebResponse', array(), array( $url ) );
	}

	private function createFactory( Controller $controller = null ) {
		$factory = $this->getMock( '\Mend\Mvc\ControllerFactory', array( 'createController' ), array( array() ) );

		$factory->expects( self::any() )
			->method( 'createController' )
			->will( self::returnValue( $controller ) );

		return $factory;
	}

	private function createController( WebRequest $request, WebResponse $response, ViewRenderer $renderer ) {
		$factory = $this->createFactory();
		$controller = $this->getMock(
			'\Mend\Rest\ResourceController',
			array( 'actionIndex', 'actionRead', 'actionCreate', 'actionUpdate', 'actionDelete', 'render' ),
			array( $request, $response, $factory, $renderer )
		);

		return $controller;
	}
}
