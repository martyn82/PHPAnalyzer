<?php
namespace Mend\Mvc;

class ViewTest extends \TestCase {
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
		$templateFile = $this->createFile( 'test:///foo.phtml' );

		$view = new View();
		$output = $view->render( $templateFile );

		self::assertTrue( is_string( $output ) );
	}

	/**
	 * @expectedException \Mend\Mvc\View\ViewException
	 */
	public function testRenderNonExistentFile() {
		$templateFile = $this->createFile( 'test:///foo.phtml', false );

		$view = new View();
		$output = $view->render( $templateFile );

		self::fail( "Test should have triggered an exception." );
	}

	private function createFile( $location, $exists = true ) {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getName', 'exists' ), array( $location ) );

		$file->expects( self::any() )
			->method( 'exists' )
			->will( self::returnValue( $exists ) );

		$file->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $location ) );

		return $file;
	}
}
