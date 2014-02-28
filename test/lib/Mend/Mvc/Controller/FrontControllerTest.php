<?php
namespace Mend\Mvc\Controller;

use Mend\Mvc\Controller;
use Mend\Mvc\ControllerException;
use Mend\Network\Web\Url;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Mvc\ViewRendererOptions;
use Mend\Collections\Map;

// mocked functions {
function class_exists( $class_name, $auto_load ) {
	return FrontControllerTest::class_exists( $class_name, $auto_load );
}
// }

class FrontControllerTest extends \TestCase {
	private static $classExistsResult;

	public static function class_exists( $className, $autoLoad ) {
		if ( is_null( self::$classExistsResult ) ) {
			return \class_exists( $className, $autoLoad );
		}

		return self::$classExistsResult;
	}

	public function setUp() {
		self::$classExistsResult = null;
	}

	public function tearDown() {
		self::$classExistsResult = null;
	}

	public function testDispatch() {
		self::$classExistsResult = true;

		$url = $this->createUrl( 'http://www.example.org/controller/action' );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createRenderer();
		$loader = $this->createLoader( 'foo' );

		$layout = $this->getMock( '\Mend\Mvc\Layout' );

		$controller = new FrontController( $request, $response, $renderer, $loader );
		$controller->setLayout( $layout );
		$controller->dispatch( 'foo', 'bar' );
	}

	/**
	 * @dataProvider urlProvider
	 *
	 * @param string $urlString
	 */
	public function testDispatchRequest( $urlString ) {
		$url = $this->createUrl( $urlString );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createRenderer();
		$loader = new ControllerLoader( array( 'Mend\Mvc\Controller' ) );

		$controller = new FrontController( $request, $response, $renderer, $loader );
		$controller->dispatchRequest();
	}

	public function urlProvider() {
		return array(
			array( 'http://www.example.org/foo/bar' ),
			array( 'http://www.example.org' )
		);
	}

	public function testSendResponse() {
		self::markTestIncomplete( "Should test sendResponse, but cannot because of print statement." );
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
		return $this->getMock( '\Mend\Mvc\ViewRenderer', array(), array( new ViewRendererOptions() ) );
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
