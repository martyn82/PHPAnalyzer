<?php
namespace Mend;

use Mend\Network\Web\Url;
use Mend\Network\Web\HttpMethod;
use Mend\Mvc\Controller;
use Mend\Mvc\Controller\FrontController;
use Mend\Mvc\Controller\PageController;

global $_SERVER_BACKUP;
$_SERVER_BACKUP = $_SERVER;

class ApplicationTest extends \TestCase {
	public function setUp() {
		global $_SERVER_BACKUP;
		$_SERVER = $_SERVER_BACKUP;
	}

	public function tearDown() {
		global $_SERVER_BACKUP;
		$_SERVER = $_SERVER_BACKUP;
	}

	/**
	 * @dataProvider methodProvider
	 *
	 * @param string $method
	 * @param string $uri
	 */
	public function testRun( $method, $uri ) {
		self::markTestSkipped();

		$this->prepareGlobals( $method, $uri );
		$config = $this->createConfig();

		$application = new Application( $config );
		$application->run();

		self::assertInstanceOf( '\Mend\Mvc\Controller\FrontController', $application->getController() );
	}

	public function methodProvider() {
		return array(
			array( HttpMethod::METHOD_DELETE, '/foo/bar' ),
			array( HttpMethod::METHOD_GET, '/foo/bar' ),
			array( HttpMethod::METHOD_HEAD, '/foo/bar' ),
			array( HttpMethod::METHOD_OPTIONS, '/foo/bar' ),
			array( HttpMethod::METHOD_PATCH, '/foo/bar' ),
			array( HttpMethod::METHOD_POST, '/foo/bar' ),
			array( HttpMethod::METHOD_PUT, '/foo/bar' )
		);
	}

	private function createConfig() {
		$config = $this->getMock(
			'\Mend\Config\ConfigProvider',
			array(),
			array( 'getArray', 'getString' ),
			'',
			false
		);

		$config->expects( self::any() )
			->method( 'getString' )
			->will(
				self::returnCallback(
					function ( $key ) {
						switch ( $key ) {
							case ApplicationConfigKey::CONTROLLER_CLASS_MAIN:
								return '\Mend\MockController';
							case ApplicationConfigKey::CONTROLLER_CLASS_SUFFIX:
								return 'Controller';
							case ApplicationConfigKey::VIEW_PATH:
								return 'test:///views';
						}
					}
				)
			);

		$config->expects( self::any() )
			->method( 'getBoolean' )
			->will(
				self::returnCallback(
					function ( $key ) {
						switch ( $key ) {
							case ApplicationConfigKey::LAYOUT_ENABLED:
								return true;
						}
					}
				)
			);

		$config->expects( self::any() )
			->method( 'getArray' )
			->will(
				self::returnCallback(
					function ( $key ) {
						switch ( $key ) {
							case ApplicationConfigKey::CONTROLLER_CLASS_NAMESPACES:
								return array( 'Mend' );
						}
					}
				)
			);

		return $config;
	}

	private function prepareGlobals( $method, $uri ) {
		$_SERVER[ 'REQUEST_METHOD' ] = $method;
		$_SERVER[ 'REQUEST_URI' ] = $uri;
		$_SERVER[ 'HTTP_HOST' ] = 'www.example.org';
		$_SERVER[ 'QUERY_STRING' ] = parse_url( $uri, PHP_URL_QUERY);
	}
}

class MockController extends FrontController {}
class FooController extends PageController {
	public function actionBar() {
	}
}
