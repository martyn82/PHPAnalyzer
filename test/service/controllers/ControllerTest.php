<?php
namespace Controller;

use Mend\Collections\Map;
use Mend\Network\Web\Url;

abstract class ControllerTest extends \TestCase {
	protected function createUrl( $urlString ) {
		return Url::createFromString( $urlString );
	}

	protected function createRequest( Url $url ) {
		return $this->getMock( '\Mend\Network\Web\WebRequest', array(), array( $url ) );
	}

	protected function createResponse( Url $url ) {
		$response = $this->getMock( '\Mend\Network\Web\WebResponse', array(), array( $url ) );

		$response->expects( self::any() )
		->method( 'getHeaders' )
		->will( self::returnValue( new Map() ) );

		return $response;
	}

	protected function createFactory() {
		return $this->getMock( '\Mend\Mvc\ControllerFactory', array(), array( array() ) );
	}

	protected function createViewRenderer() {
		return $this->getMock( '\Mend\Mvc\View\ViewRenderer', array(), array(), '', false );
	}
}
