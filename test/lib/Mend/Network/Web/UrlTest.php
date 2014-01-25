<?php
namespace Mend\Network\Web;

class UrlTest extends \TestCase {
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
}