<?php
namespace Mend\Mvc\Controller;

use Mend\Collections\Map;
use Mend\Mvc\Context;
use Mend\Mvc\View;
use Mend\Mvc\View\Layout;
use Mend\Mvc\View\ViewOptions;
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
		$viewOptions = $this->createViewOptions( $enableRender, true );
		$renderer = $this->createViewRenderer( $viewOptions, $view, $layout );
		$context = $this->createContext();

		$controller = new DummyPageController( $request, $response, $factory, $renderer, $context );
		$controller->dispatchAction( 'bar' );

		self::assertEquals( 'bar', $controller->getActionName() );
		self::assertEquals( $request, $controller->getRequest() );
		self::assertEquals( $response, $controller->getResponse() );
		self::assertEquals( $view, $controller->getView() );
		self::assertEquals( $layout, $controller->getLayout() );
		self::assertEquals( $renderer, $controller->getViewRenderer() );
		self::assertEquals( $context, $controller->getContext() );

		$newContext = $this->createContext();
		$controller->setContext( $newContext );

		self::assertEquals( $newContext, $controller->getContext() );
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
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$controller = new DummyPageController( $request, $response, $factory, $renderer, $context );
		$controller->dispatchAction( 'non' );

		self::fail( "Test shoud have triggered an exception" );
	}

	private function createContext() {
		return $this->getMock( '\Mend\Mvc\Context', array(), array(), '', false );
	}

	private function createViewOptions( $enableRender = true, $enableLayout = true ) {
		$options = $this->getMock( '\Mend\Mvc\View\ViewOptions' );

		$options->expects( self::any() )
			->method( 'getLayoutEnabled' )
			->will( self::returnValue( $enableLayout ) );

		$options->expects( self::any() )
			->method( 'getRendererEnabled' )
			->will( self::returnValue( $enableRender ) );

		return $options;
	}

	private function createViewRenderer( ViewOptions $options = null, View $view = null, Layout $layout = null ) {
		$view = $view ? : $this->createView();
		$options = $options ? : $this->createViewOptions();

		$renderer = $this->getMock(
			'\Mend\Mvc\View\ViewRenderer',
			array( 'render', 'isEnabled', 'isDisabled' ),
			array( $options, $view, $layout )
		);

		$renderer->expects( self::any() )
			->method( 'isDisabled' )
			->will( self::returnValue( !$options->getRendererEnabled() ) );

		$renderer->expects( self::any() )
			->method( 'isEnabled' )
			->will( self::returnValue( $options->getRendererEnabled() ) );

		return $renderer;
	}

	private function createLayout() {
		return $this->getMock( '\Mend\Mvc\View\Layout' );
	}

	private function createView() {
		return $this->getMock( '\Mend\Mvc\View' );
	}

	private function createRequest( Url $url ) {
		return $this->getMock( '\Mend\Network\Web\WebRequest', array(), array( $url ) );
	}

	private function createResponse( Url $url ) {
		$response = $this->getMock( '\Mend\Network\Web\WebResponse', array( 'getHeaders' ), array( $url ) );

		$response->expects( self::any() )
			->method( 'getHeaders' )
			->will( self::returnValue( new Map() ) );

		return $response;
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

	public function getView() {
		return parent::getView();
	}

	public function getLayout() {
		return parent::getLayout();
	}

	public function getViewRenderer() {
		return parent::getViewRenderer();
	}

	public function getContext() {
		return parent::getContext();
	}

	public function setContext( Context $context ) {
		parent::setContext( $context );
	}
}
