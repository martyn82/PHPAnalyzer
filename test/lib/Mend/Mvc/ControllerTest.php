<?php
namespace Mend\Mvc;

use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Network\Web\Url;

class ControllerTest extends \TestCase {
	public function testDispatch() {
		$url = Url::createFromString( 'http://www.example.org/controller/action' );

		$request = $this->getMock( '\Mend\Network\Web\WebRequest', array(), array( $url ) );
		$response = $this->getMock( '\Mend\Network\Web\WebResponse', array(), array( $url ) );

		$action = 'foo';

		$controller = $this->getMock(
			'\Mend\Mvc\DummyController',
			array( 'init', 'preDispatch', 'postDispatch', 'action' . ucfirst( $action ) ),
			array( $request, $response )
		);
		/* @var $controller DummyController */

		$controller->expects( self::once() )
			->method( 'init' );

		$controller->expects( self::once() )
			->method( 'preDispatch' );

		$controller->expects( self::once() )
			->method( 'postDispatch' );

		$controller->expects( self::once() )
			->method( 'action' . ucfirst( $action ) );

		$controller->__construct( $request, $response );
		$controller->dispatch( $action );

		self::assertEquals( $action, $controller->getActionName() );
		self::assertEquals( 'mock_dummy', $controller->getControllerName() );

		self::assertInstanceOf( '\Mend\Mvc\Layout', $controller->getLayout() );
		self::assertInstanceOf( '\Mend\Mvc\View', $controller->getView() );
	}

	/**
	 * @expectedException \Mend\Mvc\ControllerException
	 */
	public function testDispatchNonExistentAction() {
		$url = Url::createFromString( 'http://www.example.org/controller/action' );

		$request = $this->getMock( '\Mend\Network\Web\WebRequest', array(), array( $url ) );
		$response = $this->getMock( '\Mend\Network\Web\WebResponse', array(), array( $url ) );

		$controller = new DummyController( $request, $response );

		$controller->dispatch( 'non' );

		self::fail( "Test should have triggered an exception." );
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
