<?php
namespace Mend\Mvc\View;

class LayoutTest extends \TestCase {
	public function testRender() {
		$culture = $this->createCulture();

		$layout = new Layout();

		$layout->setTitle( 'title' );
		$layout->setContent( 'content' );
		$layout->setCulture( $culture );

		$scriptFile = 'test:///foo.phtml';
		$output = $layout->render( $scriptFile );

		self::assertTrue( is_string( $output ) );
	}

	public function testRenderNoCulture() {
		$layout = new Layout();

		$layout->setTitle( 'title' );
		$layout->setContent( 'content' );

		$scriptFile = 'test:///foo.phtml';
		$output = $layout->render( $scriptFile );

		self::assertTrue( is_string( $output ) );
	}

	public function testRenderNoContent() {
		$layout = new Layout();

		$layout->setTitle( 'title' );

		$scriptFile = 'test:///foo.phtml';
		$output = $layout->render( $scriptFile );

		self::assertTrue( is_string( $output ) );
	}

	public function testRenderNoTitle() {
		$layout = new Layout();

		$scriptFile = 'test:///foo.phtml';
		$output = $layout->render( $scriptFile );

		self::assertTrue( is_string( $output ) );
	}

	private function createCulture() {
		return $this->getMock( '\Mend\I18n\Culture', array(), array(), '', false );
	}
}
