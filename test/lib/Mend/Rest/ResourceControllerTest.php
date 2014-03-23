<?php
namespace Mend\Rest;

use Mend\Collections\Map;
use Mend\Network\Web\Url;

class ResourceControllerTest extends \TestCase {
	/**
	 * @dataProvider actionProvider
	 *
	 * @param string $action
	 */
	public function testDispatch( $action ) {
		$urlString = 'http://www.example.org/foo/bar';
		$query = '/foo/bar';

		$url = $this->createUrl( $urlString );
		$request = $this->createRequest( $url, $query );
		$response = $this->createResponse( $url );
		$factory = $this->createFactory();
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$controller = new DummyResourceController( $request, $response, $factory, $renderer, $context );
		$controller->dispatchAction( $action );

		$result = $this->getMockBuilder( '\Mend\Rest\ResourceResult' )
			->setConstructorArgs( array( array() ) )
			->getMock();

		$controller->setResult( $result );

		self::assertEquals( $result, $controller->getResult() );
		self::assertEquals( ResourceController::FIRST_PAGE, $controller->getPageNumber() );
		self::assertEquals( 0, $controller->getOffset() );

		$resourceId = mt_rand( 0, PHP_INT_MAX );
		$controller->setResourceId( $resourceId );

		self::assertEquals( $resourceId, $controller->getResourceId() );
	}

	public function actionProvider() {
		return array(
			array( RestAction::ACTION_CREATE ),
			array( RestAction::ACTION_DELETE ),
			array( RestAction::ACTION_INDEX ),
			array( RestAction::ACTION_READ ),
			array( RestAction::ACTION_UPDATE )
		);
	}

	private function createUrl( $urlString ) {
		return $this->getMock( '\Mend\Network\Web\Url', array(), array( $urlString ), '', false );
	}

	private function createRequest( Url $url, $query ) {
		$request = $this->getMock( '\Mend\Network\Web\WebRequest', array(), array( $url ) );

		$request->expects( self::any() )
			->method( 'getParameters' )
			->will( self::returnValue( new Map() ) );

		$request->expects( self::any() )
			->method( 'getQuery' )
			->will( self::returnValue( $query ) );

		return $request;
	}

	private function createResponse( Url $url ) {
		$response = $this->getMockBuilder( '\Mend\Network\Web\WebResponse' )
			->setConstructorArgs( array( $url ) )
			->getMock();

		$map = $this->getMock( '\Mend\Collections\Map' );

		$response->expects( self::any() )
			->method( 'getHeaders' )
			->will( self::returnValue( $map ) );

		return $response;
	}

	private function createFactory() {
		return $this->getMockBuilder( '\Mend\Mvc\ControllerFactory' )
			->setConstructorArgs( array( array() ) )
			->getMock();
	}

	private function createViewRenderer() {
		$renderer = $this->getMockBuilder( '\Mend\Mvc\View\ViewRenderer' )
			->setMethods( array( 'isEnabled', 'isDisabled' ) )
			->disableOriginalConstructor()
			->getMock();

		$renderer->expects( self::any() )
			->method( 'isEnabled' )
			->will( self::returnValue( true ) );

		$renderer->expects( self::any() )
			->method( 'isDisabled' )
			->will( self::returnValue( false ) );

		return $renderer;
	}

	private function createContext() {
		return $this->getMockBuilder( '\Mend\Mvc\Context' )
			->disableOriginalConstructor()
			->getMock();
	}
}

class DummyResourceController extends ResourceController {
	public function actionIndex() {}
	public function actionRead() {}
	public function actionCreate() {}
	public function actionUpdate() {}
	public function actionDelete() {}

	public function setResult( ResourceResult $result ) {
		parent::setResult( $result );
	}

	public function getResult() {
		return parent::getResult();
	}

	public function getPageNumber() {
		return parent::getPageNumber();
	}

	public function getOffset() {
		return parent::getOffset();
	}
}