<?php
namespace Mend\Network\Web;

use Mend\Collections\Map;

global $_BACKUP_SERVER;
$_BACKUP_SERVER = $_SERVER;

class WebRequestTest extends \TestCase {
	public function setUp() {
		global $_BACKUP_SERVER;
		$_SERVER = $_BACKUP_SERVER;
	}

	public function tearDown() {
		global $_BACKUP_SERVER;
		$_SERVER = $_BACKUP_SERVER;
	}

	public function testCreateFromGlobals() {
		$_SERVER[ 'HTTPS' ] = true;
		$_SERVER[ 'HTTP_HOST' ] = 'www.example.org';
		$_SERVER[ 'REQUEST_METHOD' ] = 'GET';
		$_SERVER[ 'REQUEST_URI' ] = '/path';
		$_SERVER[ 'QUERY_STRING' ] = 'foo=bar&baz=bar';
		$_SERVER[ 'CONTENT_TYPE' ] = 'text/html;charset=utf-8';
		$_SERVER[ 'HTTP_ACCEPT' ] = '*/*';

		$request = WebRequest::createFromGlobals();

		self::assertEquals( '*/*', $request->getHeaders()->get( 'Accept' ) );
		self::assertEquals( 'www.example.org', $request->getHeaders()->get( 'Host' ) );
		self::assertEquals( 'text/html;charset=utf-8', $request->getHeaders()->get( 'Content-Type' ) );

		self::assertEquals( 'bar', $request->getParameters()->get( 'foo' ) );
		self::assertEquals( 'bar', $request->getParameters()->get( 'baz' ) );

		self::assertEquals( 'GET', $request->getMethod() );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testCreateFromGlobalsInsufficient() {
		WebRequest::createFromGlobals();
		self::fail( "Test should have thrown an exception." );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testCreateFromGlobalsWithoutData() {
		unset( $_SERVER ); // warning: make sure it is restored in tearDown() and setUp()
		WebRequest::createFromGlobals();
		self::fail( "Test should have thrown an exception." );
	}

	public function testConstruct() {
		$url = Url::createFromString( 'http://www.example.org/path?foo=bar&baz=true' );

		$parameters = new Map();
		$parameters->addAll( array( 'baz' => 'false' ) );

		$request = new WebRequest( $url, HttpMethod::METHOD_OPTIONS, null, $parameters, 'Request body' );

		self::assertEquals( HttpMethod::METHOD_OPTIONS, $request->getMethod() );
		self::assertEquals( 0, $request->getHeaders()->getSize() );
		self::assertEquals( 'bar', $request->getParameters()->get( 'foo' ) );
		self::assertEquals( 'false', $request->getParameters()->get( 'baz' ) );
		self::assertEquals( 'Request body', $request->getBody() );
	}
}