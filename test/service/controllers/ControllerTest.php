<?php
namespace Controller;

use Mend\Collections\Map;
use Mend\Network\Web\Url;

abstract class ControllerTest extends \TestCase {
	protected function createUrl( $urlString ) {
		return Url::createFromString( $urlString );
	}

	protected function createRequest( Url $url, Map $parameters = null ) {
		$parameters = $parameters ? : new Map();
		$request = $this->getMock( '\Mend\Network\Web\WebRequest', array( 'getParameters' ), array( $url ) );

		$request->expects( self::any() )
			->method( 'getParameters' )
			->will( self::returnValue( $parameters ) );

		return $request;
	}

	protected function createResponse( Url $url ) {
		$response = $this->getMockBuilder( '\Mend\Network\Web\WebResponse' )
			->setConstructorArgs( array( $url ) )
			->getMock();

		$response->expects( self::any() )
			->method( 'getHeaders' )
			->will( self::returnValue( new Map() ) );

		return $response;
	}

	protected function createFactory() {
		return $this->getMockBuilder( '\Mend\Mvc\ControllerFactory' )
			->setConstructorArgs( array( array() ) )
			->getMock();
	}

	protected function createViewRenderer() {
		return $this->getMockBuilder( '\Mend\Mvc\View\ViewRenderer' )
			->disableOriginalConstructor()
			->getMock();
	}

	protected function createContext() {
		return $this->getMockBuilder( '\Mend\Mvc\Context' )
			->disableOriginalConstructor()
			->getMock();
	}
}
