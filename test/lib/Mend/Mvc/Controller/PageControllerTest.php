<?php
namespace Mend\Mvc\Controller;

use Mend\Network\Web\Url;

class PageControllerTest extends \TestCase {
	public function testDispatch() {
		$url = Url::createFromString( 'http://www.example.org/foo/bar' );

		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$factory = $this->createFactory();

		$controller = new DummyPageController( $request, $response, $factory );
		$controller->dispatchAction( 'bar' );

		self::assertEquals( 'bar', $controller->getActionName() );
	}

	/**
	 * @expectedException \Mend\Mvc\Controller\ControllerException
	 */
	public function testDispatchUnknownAction() {
		$url = Url::createFromString( 'http://www.example.org/foo/bar' );

		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$factory = $this->createFactory();

		$controller = new DummyPageController( $request, $response, $factory );
		$controller->dispatchAction( 'non' );

		self::fail( "Test shoud have triggered an exception" );
	}

	private function createRequest( Url $url ) {
		return $this->getMock( '\Mend\Network\Web\WebRequest', array(), array( $url ) );
	}

	private function createResponse( Url $url ) {
		return $this->getMock( '\Mend\Network\Web\WebResponse', array(), array( $url ) );
	}

	private function createFactory() {
		return $this->getMock( '\Mend\Mvc\ControllerFactory', array(), array( array() ) );
	}
}

class DummyPageController extends PageController {
	public function actionBar() {}

	public function getActionName() {
		return parent::getActionName();
	}
}
