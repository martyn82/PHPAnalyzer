<?php
namespace Controller;

require_once "ControllerTest.php";

class IndexControllerTest extends ControllerTest {
	public function testDispatchIndex() {
		$urlString = 'http://www.example.org/foo/bar';
		$url = $this->createUrl( $urlString );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$factory = $this->createFactory();
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$controller = new IndexController( $request, $response, $factory, $renderer, $context );
		$controller->dispatchAction( 'index' );
	}
}
