<?php
namespace Mend\Mvc\Controller;

use Mend\Collections\Map;
use Mend\Mvc\Controller;
use Mend\Mvc\ControllerFactory;
use Mend\Network\Web\Url;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Mvc\View\ViewRenderer;
use Mend\Mvc\Context;

class FrontControllerTest extends \TestCase {
	public function testConstruct() {
		$url = $this->createUrl( 'http://www.example.org/controller/action' );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$factory = $this->createFactory();
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$controller = new FrontController( $request, $response, $factory, $renderer, $context );

		self::assertEquals( $request, $controller->getRequest() );
		self::assertEquals( $response, $controller->getResponse() );
	}

	/**
	 * @dataProvider urlProvider
	 *
	 * @param Url $url
	 * @param string $controllerName
	 * @param string $actionName
	 */
	public function testDispatch( Url $url, $controllerName, $actionName ) {
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$created = $this->createPageController( $request, $response, $renderer, $context );
		$factory = $this->createFactory( $created );

		$controller = new DummyFrontController( $request, $response, $factory, $renderer, $context );
		$controller->dispatchRequest();

		self::assertEquals( $controllerName, $controller->getControllerName() );
		self::assertEquals( $actionName, $controller->getActionName() );
	}

	public function urlProvider() {
		return array(
			array( $this->createUrl( 'http://www.example.org/foo/bar' ), 'foo', 'bar' ),
			array( $this->createUrl( 'http://www.example.org/foo/bar/key/value' ), 'foo', 'bar' )
		);
	}

	/**
	 * @dataProvider urlParamProvider
	 *
	 * @param string $urlString
	 * @param array $parameters
	 */
	public function testDispatchRequest( $urlString, array $parameters ) {
		$url = $this->createUrl( $urlString );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$created = $this->createPageController( $request, $response, $renderer, $context );
		$factory = $this->createFactory( $created );

		$controller = new DummyFrontController( $request, $response, $factory, $renderer, $context );
		$controller->dispatchRequest();

		$request = $controller->getRequest();
		$params = $request->getParameters();

		self::assertEquals( $parameters, $params->toArray() );
	}

	public function urlParamProvider() {
		return array(
			array( 'http://www.example.org/foo/bar', array() ),
			array( 'http://www.example.org', array() ),
			array( 'http://www.example.co.org/foo/bar/baz/boo/faz', array( 'baz' => 'boo', 'faz' => null ) ),
			array( 'http://www.example.co.org/foo/bar?baz=boo&faz=1', array( 'baz' => 'boo', 'faz' => 1 ) ),
			array( 'http://www.example.co.org/foo/bar/baz/boz?baz=boo&faz=1', array( 'baz' => 'boz', 'faz' => 1 ) )
		);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testDispatchNonExistentController() {
		$url = $this->createUrl( 'http://www.example.org/controller/action' );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$context = $this->createContext();
		$factory = new ControllerFactory( array() );

		$controller = new DummyFrontController( $request, $response, $factory, $context );
		$controller->dispatch( 'foo', 'bar' );

		self::fail( "Test should have triggered an exception." );
	}

	private function createViewRenderer() {
		return $this->getMockBuilder( '\Mend\Mvc\View\ViewRenderer' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createController( WebRequest $request, WebResponse $response ) {
		$factory = $this->createFactory( null );

		$controller = $this->getMock(
			'\Mend\Mvc\Controller',
			array( 'getControllerName', 'getActionName', 'dispatchAction' ),
			array( $request, $response, $factory )
		);

		return $controller;
	}

	private function createPageController(
		WebRequest $request,
		WebResponse $response,
		ViewRenderer $renderer,
		Context $context
	) {
		$factory = $this->createFactory( null );

		$controller = $this->getMock(
			'\Mend\Mvc\Controller\PageController',
			array( 'getControllerName', 'getActionName', 'dispatchAction' ),
			array( $request, $response, $factory, $renderer, $context )
		);

		return $controller;
	}

	private function createContext() {
		return $this->getMockBuilder( '\Mend\Mvc\Context' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createFactory( PageController $controller = null ) {
		$factory = $this->getMockBuilder( '\Mend\Mvc\ControllerFactory' )
			->setMethods( array( 'createController' ) )
			->setConstructorArgs( array( array() ) )
			->getMock();

		if ( !is_null( $controller ) ) {
			$controller->expects( self::any() )
				->method( 'dispatchAction' );
		}

		$factory->expects( self::any() )
			->method( 'createController' )
			->will( self::returnValue( $controller ) );

		return $factory;
	}

	private function createRequest( Url $url ) {
		return new WebRequest( $url );
	}

	private function createResponse( Url $url ) {
		return $this->getMockBuilder( '\Mend\Network\Web\WebResponse' )
			->setConstructorArgs( array( $url ) )
			->getMock();
	}

	private function createUrl( $urlString ) {
		return Url::createFromString( $urlString );
	}
}

class DummyFrontController extends FrontController {
	public function getControllerName() {
		return parent::getControllerName();
	}

	public function getActionName() {
		return parent::getActionName();
	}
}
