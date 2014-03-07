<?php
namespace Mend\Mvc\View;

// mocked functions {
function is_dir( $filename ) {
	return ViewRendererTest::is_dir( $filename );
}
// }

class ViewRendererTest extends \TestCase {
	private static $isDirResult;

	public static function is_dir( $fileName ) {
		if ( is_null( self::$isDirResult ) ) {
			return \is_dir( $fileName );
		}

		return self::$isDirResult;
	}

	public function setUp() {
		self::$isDirResult = null;
	}

	public function tearDown() {
		self::$isDirResult = null;
	}

	public function testRenderView() {
		$options = $this->createOptions();
		$renderer = new ViewRenderer( $options );

		$output = 'output';

		$view = $this->createView();
		$view->expects( self::any() )
			->method( 'render' )
			->will( self::returnValue( $output ) );

		$templateFile = 'test:///foo.phtml';

		$result = $renderer->renderView( $view, $templateFile );

		self::assertTrue( is_string( $result ) );
		self::assertEquals( $output, $result );
	}

	public function testRenderLayout() {
		$options = $this->createOptions();
		$renderer = new ViewRenderer( $options );

		$output = 'output';

		$layout = $this->createLayout();
		$layout->expects( self::any() )
			->method( 'render' )
			->will( self::returnValue( $output ) );

		$templateFile = 'test:///foo.phtml';

		$result = $renderer->renderLayout( $layout, $templateFile );

		self::assertTrue( is_string( $result ) );
		self::assertEquals( $output, $result );
	}

	/**
	 * @expectedException \Mend\Mvc\View\ViewException
	 */
	public function testRenderInvalidTemplatePath() {
		self::$isDirResult = false;

		$options = $this->createOptions();
		$renderer = new ViewRenderer( $options );

		$output = 'output';

		$view = $this->createView();
		$templateFile = 'test:///foo.phtml';

		$result = $renderer->renderView( $view, $templateFile );

		self::fail( "Test should have triggered an exception." );
	}

	private function createOptions() {
		return $this->getMock( '\Mend\Mvc\View\ViewRendererOptions' );
	}

	private function createView() {
		return $this->getMock( '\Mend\Mvc\View', array( 'render' ) );
	}

	private function createLayout() {
		return $this->getMock( '\Mend\Mvc\View\Layout', array( 'render' ) );
	}
}
