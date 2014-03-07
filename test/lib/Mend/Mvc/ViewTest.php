<?php
namespace Mend\Mvc;

// mock function {
function file_exists( $filename ) {
	return ViewTest::file_exists( $filename );
}
// }

class ViewTest extends \TestCase {
	private static $fileExistsResult;

	public static function file_exists( $fileName ) {
		if ( is_null( self::$fileExistsResult ) ) {
			return \file_exists( $fileName );
		}

		return self::$fileExistsResult;
	}

	public function setUp() {
		self::$fileExistsResult = null;
	}

	public function tearDown() {
		self::$fileExistsResult = null;
	}

	/**
	 * @dataProvider variableProvider
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param mixed $expected
	 */
	public function testAssign( $name, $value, $expected ) {
		$view = new View();
		$view->assign( $name, $value );

		self::assertEquals( $expected, $view->$name );
		self::assertEquals( $value, $view->noEscape( $name ) );
	}

	public function variableProvider() {
		return array(
			array( 'foo', 'bar', 'bar' ),
			array( 'baz', 'this & that', 'this &amp; that' ),
			array( 'boo', 12, 12 )
		);
	}

	/**
	 * @expectedException \Mend\Mvc\View\ViewException
	 */
	public function testGetNonExistent() {
		$non = 'non';

		$view = new View();
		$view->$non;

		self::fail( "Test should have triggered an exception." );
	}

	public function testToString() {
		$view = new View();
		$view->assign( 'foo', 'bar' );
		$view->assign( 'baz', 'boo' );

		self::assertNotNull( $view->__toString() );
	}

	public function testRender() {
		self::$fileExistsResult = true;
		$templateFile = 'test:///foo.phtml';

		$view = new View();
		$output = $view->render( $templateFile );

		self::assertTrue( is_string( $output ) );
	}

	/**
	 * @expectedException \Mend\Mvc\View\ViewException
	 */
	public function testRenderNonExistentFile() {
		self::$fileExistsResult = false;
		$templateFile = 'test:///foo.phtml';

		$view = new View();
		$output = $view->render( $templateFile );

		self::fail( "Test should have triggered an exception." );
	}
}
