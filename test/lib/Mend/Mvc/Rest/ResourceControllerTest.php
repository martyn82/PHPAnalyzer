<?php
namespace Mend\Mvc\Rest;

use Mend\Network\Web\Url;

class ResourceControllerTest extends \TestCase {
	private static $actionReadStub;
	private static $actionIndexStub;
	private static $actionCreateStub;
	private static $actionUpdateStub;
	private static $actionDeleteStub;

	public static function actionReadStub() {
		call_user_func( self::$actionReadStub );
	}

	public static function actionIndexStub() {
		call_user_func( self::$actionIndexStub );
	}

	public static function actionCreateStub() {
		call_user_func( self::$actionCreateStub );
	}

	public static function actionUpdateStub() {
		call_user_func( self::$actionUpdateStub );
	}

	public static function actionDeleteStub() {
		call_user_func( self::$actionDeleteStub );
	}

	/**
	 * @dataProvider stubProvider
	 *
	 * @param string $action
	 */
	public function testDispatch( $action ) {
		$request = $this->getMock( '\Mend\Network\Web\WebRequest', array(), array(), '', false );
		$response = $this->getMock( '\Mend\Network\Web\WebResponse', array(), array(), '', false );
		$renderer = $this->getMock( '\Mend\Mvc\View\ViewRenderer', array(), array(), '', false );
		$loader = $this->getMock(
			'\Mend\Mvc\Controller\ControllerLoader',
			array( 'getControllerClassName' ),
			array(),
			'',
			false
		);

		$loader->expects( self::any() )
			->method( 'getControllerClassName' )
			->will( self::returnValue( '\Mend\Mvc\Rest\DummyResourceController' ) );

		self::$actionReadStub = function () use ( $action ) {
			self::assertTrue( $action == 'read' );
		};
		self::$actionDeleteStub = function () use ( $action ) {
			self::assertTrue( $action == 'delete' );
		};
		self::$actionCreateStub = function () use ( $action ) {
			self::assertTrue( $action == 'create' );
		};
		self::$actionIndexStub = function () use ( $action ) {
			self::assertTrue( $action == 'index' );
		};
		self::$actionUpdateStub = function () use ( $action ) {
			self::assertTrue( $action == 'update' );
		};

		$rest = new RestController( $request, $response, $renderer, $loader );
		$rest->dispatch( 'dummy', $action );
	}

	public function stubProvider() {
		return array(
			array( 'index' ),
			array( 'read' ),
			array( 'create' ),
			array( 'update' ),
			array( 'delete' )
		);
	}
}

class DummyResourceController extends ResourceController {
	public function actionRead() {
		ResourceControllerTest::actionReadStub();
	}

	public function actionIndex() {
		ResourceControllerTest::actionIndexStub();
	}

	public function actionCreate() {
		ResourceControllerTest::actionCreateStub();
	}

	public function actionUpdate() {
		ResourceControllerTest::actionUpdateStub();
	}

	public function actionDelete() {
		ResourceControllerTest::actionDeleteStub();
	}
}
