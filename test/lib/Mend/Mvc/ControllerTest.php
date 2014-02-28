<?php
namespace Mend\Mvc;

use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Network\Web\Url;

class ControllerTest extends \TestCase {
	public function testConstruct() {
		$url = $this->createUrl();
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createViewRenderer();

		$controller = new DummyController( $request, $response, $renderer );

		self::assertEquals( $request, $controller->getRequest() );
		self::assertEquals( $response, $controller->getResponse() );
		self::assertEquals( 'dummy', $controller->getControllerName() );
	}

	public function testConstructWithViews() {
		$url = $this->createUrl();
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createViewRenderer();
		$view = $this->createView();
		$layout = $this->createLayout();

		$controller = new DummyController( $request, $response, $renderer, $view, $layout );

		self::assertEquals( $view, $controller->getView() );
		self::assertEquals( $layout, $controller->getLayout() );
	}

	public function testDispatch() {
		$url = $this->createUrl();
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createViewRenderer();

		$renderer->expects( self::once() )
			->method( 'renderView' );

		$renderer->expects( self::never() )
			->method( 'renderLayout' );

		$actionName = 'foo';

		$controller = new DummyController( $request, $response, $renderer );
		$controller->dispatch( $actionName );

		self::assertEquals( $actionName, $controller->getActionName() );
	}

	public function testDispatchWithLayout() {
		$url = $this->createUrl();
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createViewRenderer();

		$renderer->expects( self::once() )
			->method( 'renderView' );

		$renderer->expects( self::once() )
			->method( 'renderLayout' );

		$actionName = 'foo';
		$layout = $this->createLayout();

		$controller = new DummyController( $request, $response, $renderer, null, $layout );
		$controller->dispatch( $actionName );

		self::assertEquals( $actionName, $controller->getActionName() );
	}

	/**
	 * @expectedException \Mend\Mvc\ControllerException
	 */
	public function testDispatchNonExistentAction() {
		$url = $this->createUrl();
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$renderer = $this->createViewRenderer();

		$actionName = 'non';

		$controller = new DummyController( $request, $response, $renderer );
		$controller->dispatch( $actionName );

		self::fail( "Test should have triggered an exception." );
	}

	private function createUrl() {
		return Url::createFromString( 'http://www.example.org/controller/action' );
	}

	private function createViewRenderer() {
		return $this->getMock( '\Mend\Mvc\ViewRenderer', array(), array(), '', false );
	}

	private function createRequest( Url $url ) {
		return $this->getMock( '\Mend\Network\Web\WebRequest', array(), array( $url ) );
	}

	private function createResponse( Url $url ) {
		return $this->getMock( '\Mend\Network\Web\WebResponse', array(), array( $url ) );
	}

	private function createView() {
		return $this->getMock( '\Mend\Mvc\View' );
	}

	private function createLayout() {
		return $this->getMock( '\Mend\Mvc\Layout' );
	}
}

class DummyController extends Controller {
	public function actionFoo() { /* no-op */ }

	public function getControllerName() {
		return parent::getControllerName();
	}

	public function getActionName() {
		return parent::getActionName();
	}

	public function getView() {
		return parent::getView();
	}

	public function getLayout() {
		return parent::getLayout();
	}
}
