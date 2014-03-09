<?php
namespace Controller;

class IndexControllerTest extends ControllerTest {
	public function testDispatchIndex() {
		$urlString = 'http://www.example.org/foo/bar';
		$url = $this->createUrl( $urlString );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );
		$factory = $this->createFactory();
		$renderer = $this->createViewRenderer();

		$controller = new IndexController( $request, $response, $factory, $renderer );
		$controller->dispatchAction( 'index' );
	}
}
