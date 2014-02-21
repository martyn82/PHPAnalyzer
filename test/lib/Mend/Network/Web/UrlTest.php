<?php
namespace Mend\Network\Web;

global $_BACKUP_SERVER;
$_BACKUP_SERVER = $_SERVER;

class UrlTest extends \TestCase {
	public function setUp() {
		global $_BACKUP_SERVER;
		$_SERVER = $_BACKUP_SERVER;
	}

	public function tearDown() {
		global $_BACKUP_SERVER;
		$_SERVER = $_BACKUP_SERVER;
	}

	/**
	 * @dataProvider urlDataProvider
	 */
	public function testCreateFromString( $urlString, array $components ) {
		$url = Url::createFromString( $urlString );

		self::assertEquals( $components[ 'scheme' ], $url->getScheme() );
		self::assertEquals( $components[ 'host' ], $url->getHost() );
		self::assertEquals( $components[ 'port' ], $url->getPort() );
		self::assertEquals( $components[ 'user' ], $url->getUsername() );
		self::assertEquals( $components[ 'pass' ], $url->getPassword() );
		self::assertEquals( $components[ 'path' ], $url->getPath() );
		self::assertEquals( $components[ 'query' ], $url->getQueryString() );
		self::assertEquals( $components[ 'fragment' ], $url->getFragment() );

		self::assertEquals( $urlString, (string) $url );
	}

	public function testCreateFromGlobals() {
		$_SERVER[ 'HTTP_HOST' ] = 'www.example.org';
		$_SERVER[ 'REQUEST_URI' ] = '/index.php';
		$_SERVER[ 'QUERY_STRING' ] = 'foo=bar&bar=baz';

		$url = Url::createFromGlobals();

		self::assertEquals( 'http', $url->getScheme() );
		self::assertEquals( 'www.example.org', $url->getHost() );
		self::assertNull( $url->getUsername() );
		self::assertNull( $url->getPassword() );
		self::assertEquals( '/index.php', $url->getPath() );
		self::assertEquals( 'foo=bar&bar=baz', $url->getQueryString() );
	}

	public function urlDataProvider() {
		return array(
			array(
				'http://www.example.org/path?query=foo&string=bar#frag',
				array(
					'scheme' => 'http',
					'host' => 'www.example.org',
					'port' => null,
					'user' => null,
					'pass' => null,
					'path' => '/path',
					'query' => 'query=foo&string=bar',
					'fragment' => 'frag'
				)
			),
			array(
				'https://user:pass@foo.bar.baz.net:8001#top',
				array(
					'scheme' => 'https',
					'host' => 'foo.bar.baz.net',
					'port' => 8001,
					'user' => 'user',
					'pass' => 'pass',
					'path' => null,
					'query' => null,
					'fragment' => 'top'
				)
			)
		);
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testCreateFromGlobalsWithoutGlobals() {
		unset( $_SERVER ); // warning: make sure it is restored in tearDown() and setUp()
		Url::createFromGlobals();
		self::fail( "Test should have failed without \$_SERVER global." );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testCreateFromGlobalsInsufficient() {
		Url::createFromGlobals();
		self::fail( "Test should have failed with thin \$_SERVER global." );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCreateFromStringInvalidInput() {
		Url::createFromString( null );
		self::fail( "Test should have failed without proper input string." );
	}
}