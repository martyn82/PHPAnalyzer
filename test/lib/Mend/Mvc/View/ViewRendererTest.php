<?php
namespace Mend\Mvc\View;

class ViewRendererTest extends \TestCase {
	/**
	 * @dataProvider layoutProvider
	 *
	 * @param boolean $enabled
	 * @param Layout $layout
	 */
	public function testAccessors( $enabled, Layout $layout = null ) {
		$options = $this->createViewOptions( $enabled );
		$view = $this->createView();

		$renderer = new ViewRenderer( $options, $view, $layout );

		self::assertEquals( $view, $renderer->getView() );
		self::assertEquals( $layout, $renderer->getLayout() );
		self::assertEquals( $options, $renderer->getOptions() );
		self::assertEquals( $enabled, $renderer->isEnabled() );
		self::assertEquals( $enabled, !$renderer->isDisabled() );
	}

	public function layoutProvider() {
		return array(
			array( true, null ),
			array( true, $this->createLayout() ),
			array( false, null ),
			array( false, $this->createLayout() )
		);
	}

	/**
	 * @dataProvider booleanProvider
	 *
	 * @param boolean $enabled
	 */
	public function testEnableDisable( $enabled ) {
		$options = $this->createViewOptions( $enabled );
		$view = $this->createView();

		$renderer = new ViewRenderer( $options, $view );

		self::assertEquals( $enabled, $renderer->isEnabled() );
		self::assertEquals( $enabled, !$renderer->isDisabled() );

		$renderer->enable();

		self::assertTrue( $renderer->isEnabled() );
		self::assertFalse( $renderer->isDisabled() );

		$renderer->resetEnabled();
		self::assertEquals( $enabled, $renderer->isEnabled() );
		self::assertEquals( $enabled, !$renderer->isDisabled() );

		$renderer->disable();

		self::assertFalse( $renderer->isEnabled() );
		self::assertTrue( $renderer->isDisabled() );

		$renderer->resetEnabled();
		self::assertEquals( $enabled, $renderer->isEnabled() );
		self::assertEquals( $enabled, !$renderer->isDisabled() );
	}

	/**
	 * @dataProvider layoutProvider
	 *
	 * @param boolean $enabled
	 * @param Layout $layout
	 */
	public function testRender( $enabled, Layout $layout = null ) {
		$options = $this->createViewOptions( $enabled );
		$view = $this->createView();

		if ( !is_null( $layout ) ) {
			$options->expects( self::any() )
				->method( 'getLayoutEnabled' )
				->will( self::returnValue( true ) );
		}

		$renderer = new ViewRenderer( $options, $view, $layout );

		$file = $this->createFile();
		$renderer->render( $file );
	}

	public function booleanProvider() {
		return array(
			array( true ),
			array( false )
		);
	}

	private function createFile() {
		return $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createViewOptions( $enabled ) {
		$options = $this->getMock( '\Mend\Mvc\View\ViewOptions', array( 'getRendererEnabled', 'getLayoutEnabled' ) );

		$options->expects( self::any() )
			->method( 'getRendererEnabled' )
			->will( self::returnValue( $enabled ) );

		return $options;
	}

	private function createView() {
		return $this->getMock( '\Mend\Mvc\View' );
	}

	private function createLayout() {
		return $this->getMock( '\Mend\Mvc\View\Layout' );
	}
}
