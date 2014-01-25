<?php
namespace Mend\Network\Web;

use Mend\Collections\Map;

class WebResponseTest extends \TestCase {
	public function testConstruct() {
		$urlString = 'http://www.example.org';
		$url = Url::createFromString( $urlString );

		$headers = new Map();
		$headers->set( 'Content-type', 'text/plain' );
		$body = 'Response body';
		$response = new WebResponse( $url, $headers, $body );

		self::assertEquals( $url, $response->getUrl() );
		self::assertEquals( $headers, $response->getHeaders() );
		self::assertEquals( 'text/plain', $response->getHeaders()->get( 'Content-type' ) );
		self::assertEquals( $body, $response->getBody() );
	}

	public function testStatus() {
		$urlString = 'http://www.example.org';
		$url = Url::createFromString( $urlString );
		$response = new WebResponse( $url, null, null, HttpStatus::STATUS_INTERNAL_SERVER_ERROR, 'Oops' );

		self::assertEquals( HttpStatus::STATUS_INTERNAL_SERVER_ERROR, $response->getStatusCode() );
		self::assertEquals( 'Oops', $response->getStatusDescription() );
	}
}