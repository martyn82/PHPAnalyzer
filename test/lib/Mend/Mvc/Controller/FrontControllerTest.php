<?php
namespace Mend\Mvc\Controller;

use Mend\Collections\Map;
use Mend\Mvc\View\ViewRendererOptions;
use Mend\Network\Web\Url;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

require_once 'ControllerClassExists.php';

class FrontControllerTest extends \TestCase {
	public function setUp() {
		ControllerClassExists::$classExistsResult = null;
	}

	public function tearDown() {
		ControllerClassExists::$classExistsResult = null;
	}

	public function testDispatch() {
		ControllerClassExists::$classExistsResult = true;

		$url = $this->createUrl( 'http://www.example.org/controller/action' );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createRenderer();
		$loader = $this->createLoader( 'foo' );

		$layout = $this->getMock( '\Mend\Mvc\View\Layout' );

		$controller = new FrontController( $request, $response, $renderer, $loader );
		$controller->setLayout( $layout );
		$controller->dispatch( 'foo', 'bar' );
	}

	/**
	 * @expectedException \Mend\Mvc\Controller\ControllerException
	 */
	public function testDispatchNonExistentController() {
		ControllerClassExists::$classExistsResult = false;

		$url = $this->createUrl( 'http://www.example.org/controller/action' );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createRenderer();
		$loader = $this->createLoader( 'foo' );

		$layout = $this->getMock( '\Mend\Mvc\View\Layout' );

		$controller = new FrontController( $request, $response, $renderer, $loader );
		$controller->setLayout( $layout );
		$controller->dispatch( 'foo', 'bar' );

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @dataProvider urlProvider
	 *
	 * @param string $urlString
	 * @param array $parameters
	 */
	public function testDispatchRequest( $urlString, array $parameters ) {
		$url = $this->createUrl( $urlString );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createRenderer();
		$loader = new ControllerLoader( array( 'Mend\Mvc\Controller' ) );

		$controller = new FrontController( $request, $response, $renderer, $loader );
		$controller->dispatchRequest();

		$request = $controller->getRequest();
		$params = $request->getParameters();

		self::assertEquals( $parameters, $params->toArray() );
	}

	public function urlProvider() {
		return array(
			array( 'http://www.example.org/foo/bar', array() ),
			array( 'http://www.example.org', array() ),
			array( 'http://www.example.co.org/foo/bar/baz/boo/faz', array( 'baz' => 'boo', 'faz' => null ) ),
			array( 'http://www.example.co.org/foo/bar?baz=boo&faz=1', array( 'baz' => 'boo', 'faz' => 1 ) ),
			array( 'http://www.example.co.org/foo/bar/baz/boz?baz=boo&faz=1', array( 'baz' => 'boz', 'faz' => 1 ) )
		);
	}

	private function createUrl( $urlString ) {
		return Url::createFromString( $urlString );
	}

	private function createRequest( Url $url ) {
		$request = $this->getMock( '\Mend\Network\Web\WebRequest', array( 'getUrl' ), array( $url ) );

		$request->expects( self::any() )
			->method( 'getUrl' )
			->will( self::returnValue( $url ) );

		return $request;
	}

	private function createResponse( Url $url ) {
		return $this->getMock( '\Mend\Network\Web\WebResponse', array(), array( $url ) );
	}

	private function createRenderer() {
		return $this->getMock( '\Mend\Mvc\View\ViewRenderer', array(), array( new ViewRendererOptions() ) );
	}

	private function createLoader( $controllerName ) {
		$loader = $this->getMock(
			'\Mend\Mvc\Controller\ControllerLoader',
			array( 'getControllerClassName' ),
			array(),
			'',
			false
		);

		$loader->expects( self::any() )
			->method( 'getControllerClassName' )
			->will( self::returnValue( __NAMESPACE__ . '\\' . ucfirst( $controllerName ) . 'Controller' ) );

		return $loader;
	}
}

class FooController extends Controller {
	public function actionBar() { /* no-op */ }
}

class IndexController extends Controller {
	public function actionIndex() { /* no-op */ }
}
