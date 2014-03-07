<?php
namespace Mend\Mvc\Controller;

use Mend\Network\Web\Url;

class PageControllerTest extends \TestCase {
	/**
	 * @dataProvider switchProvider
	 *
	 * @param boloean $enableRender
	 */
	public function testDispatch( $enableRender ) {
		$url = Url::createFromString( 'http://www.example.org/foo/bar' );

		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$factory = $this->createFactory();

		$view = $this->createView();
		$layout = $this->createLayout();

		$controller = new DummyPageController( $request, $response, $factory );
		$controller->setView( $view );
		$controller->setLayout( $layout );

		$controller->enableRender( $enableRender );
		$controller->enableLayout( true );

		$controller->setViewTemplatePath( 'views/' );
		$controller->setLayoutTemplatePath( 'views/' );
		$controller->setLayoutTemplate( 'default.phtml' );

		$controller->dispatchAction( 'bar' );

		self::assertEquals( 'bar', $controller->getActionName() );
		self::assertEquals( $request, $controller->getRequest() );
		self::assertEquals( $response, $controller->getResponse() );
		self::assertEquals( $view, $controller->getView() );
		self::assertEquals( $layout, $controller->getLayout() );
	}

	public function switchProvider() {
		return array(
			array( true ),
			array( false )
		);
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

	private function createLayout() {
		return $this->getMock( '\Mend\Mvc\View\Layout' );
	}

	private function createView() {
		return $this->getMock( '\Mend\Mvc\View\View' );
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
