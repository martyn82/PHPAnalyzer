<?php
namespace Mend;

use Mend\Mvc\Context;
use Mend\Mvc\Controller;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\Controller\FrontController;
use Mend\Mvc\Controller\PageController;
use Mend\Mvc\View\ViewRenderer;

use Mend\Network\Web\HttpMethod;
use Mend\Network\Web\Url;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

global $_SERVER_BACKUP;
$_SERVER_BACKUP = $_SERVER;

class ApplicationTest extends \TestCase {
	public function setUp() {
		global $_SERVER_BACKUP;
		$_SERVER = $_SERVER_BACKUP;

		MockControllerFactory::setController( null );
	}

	public function tearDown() {
		global $_SERVER_BACKUP;
		$_SERVER = $_SERVER_BACKUP;

		MockControllerFactory::setController( null );
	}

	/**
	 * @dataProvider methodProvider
	 *
	 * @param string $method
	 * @param string $uri
	 */
	public function testRun( $method, $uri ) {
		$this->prepareGlobals( $method, $uri );

		$controller = $this->createController();
		MockControllerFactory::setController( $controller );

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

	/**
	 * @expectedException \Mend\ApplicationException
	 */
	public function testRunNoControllerFactory() {
		$this->prepareGlobals( HttpMethod::METHOD_GET, '/foo/bar' );
		$config = $this->createConfig( false );

		$application = new Application( $config );
		$application->run();

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \Mend\ApplicationException
	 */
	public function testRunNoFrontController() {
		$this->prepareGlobals( HttpMethod::METHOD_GET, '/foo/bar' );
		$config = $this->createConfig( true, false );

		$application = new Application( $config );
		$application->run();

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \Mend\ApplicationException
	 */
	public function testRunInvalidFrontController() {
		$this->prepareGlobals( HttpMethod::METHOD_GET, '/foo/bar' );
		$config = $this->createConfig( true, true, '\Mend\BarController' );

		$application = new Application( $config );
		$application->run();

		self::fail( "Test should have triggered an exception." );
	}

	public function testRunCustomContext() {
		$this->prepareGlobals( HttpMethod::METHOD_GET, '/foo/bar' );

		$controller = $this->createController();
		MockControllerFactory::setController( $controller );

		$context = $this->createContext( 'foo/bar', '.baz' );
		$config = $this->createConfig( true, true, null, $context );

		$application = new Application( $config );
		$application->run();
	}

	public function testRunPredefinedContext() {
		$this->prepareGlobals( HttpMethod::METHOD_GET, '/foo/bar' );

		$controller = $this->createController();
		MockControllerFactory::setController( $controller );

		$config = $this->createConfig( true, true, null, null, '\Mend\Mvc\Context\HtmlContext' );

		$application = new Application( $config );
		$application->run();
	}

	/**
	 * @expectedException \Mend\ApplicationException
	 */
	public function testRunNonExistentContext() {
		$this->prepareGlobals( HttpMethod::METHOD_GET, '/foo/bar' );

		$controller = $this->createController();
		MockControllerFactory::setController( $controller );

		$config = $this->createConfig( true, true, null, null, 'MockContext' );

		$application = new Application( $config );
		$application->run();

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \Mend\ApplicationException
	 */
	public function testRunInvalidContext() {
		$this->prepareGlobals( HttpMethod::METHOD_GET, '/foo/bar' );

		$controller = $this->createController();
		MockControllerFactory::setController( $controller );

		$config = $this->createConfig( true, true, null, null, '\Mend\MockController' );

		$application = new Application( $config );
		$application->run();

		self::fail( "Test should have triggered an exception." );
	}


	private function createContext( $contentType, $templateFileSuffix ) {
		$context = $this->getMock(
			'\Mend\Mvc\Context',
			array( 'getCharacterSet', 'getTemplateFileSuffix', 'getContentType' ),
			array( $contentType, $templateFileSuffix ),
			'',
			false
		);

		$context->expects( self::any() )
			->method( 'getCharacterSet' )
			->will( self::returnValue( 'utf-8' ) );

		$context->expects( self::any() )
			->method( 'getTemplateFileSuffix' )
			->will( self::returnValue( $templateFileSuffix) );

		$context->expects( self::any() )
			->method( 'getContentType' )
			->will( self::returnValue( $contentType ) );

		return $context;
	}

	private function createController() {
		$controller = $this->getMock(
			'\Mend\Mvc\Controller\PageController',
			array(),
			array(),
			'',
			false
		);

		return $controller;
	}

	private function createConfig(
		$withFactory = true,
		$withFrontController = true,
		$customFrontController = null,
		Context $context = null,
		$contextClassName = null
	) {
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
					function ( $key )
						use ( $withFactory, $withFrontController, $customFrontController, $context, $contextClassName )
					{
						switch ( $key ) {
							case ApplicationConfigKey::CONTROLLER_FACTORY:
								return $withFactory ? '\Mend\MockControllerFactory' : null;

							case ApplicationConfigKey::CONTROLLER_CLASS_FRONT:
								$customFrontController = $customFrontController ? : '\Mend\MockController';
								return $withFrontController
									? $customFrontController
									: null;

							case ApplicationConfigKey::CONTROLLER_CLASS_SUFFIX:
								return 'Controller';

							case ApplicationConfigKey::VIEW_PATH:
								return 'test:///views';

							case ApplicationConfigKey::CONTEXT_CHARSET:
								return $context ? $context->getCharacterSet() : null;

							case ApplicationConfigKey::CONTEXT_CLASS:
								return $contextClassName;

							case ApplicationConfigKey::CONTEXT_TYPE:
								return $context ? $context->getContentType() : null;

							case ApplicationConfigKey::CONTEXT_VIEW_SUFFIX:
								return $context ? $context->getTemplateFileSuffix() : null;
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
	public function actionBar() {}
}
class BarController extends Controller {
	protected function getControllerName() {
		return 'bar';
	}
	protected function getActionName() {
		return '';
	}
}
class MockControllerFactory extends ControllerFactory {
	private static $controller;

	public static function setController( Controller $controller = null ) {
		self::$controller = $controller;
	}

	public function createController(
		$controllerName,
		WebRequest $request,
		WebResponse $response,
		ViewRenderer $renderer,
		Context $context
	) {
		return self::$controller;
	}
}