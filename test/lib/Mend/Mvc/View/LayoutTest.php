<?php
namespace Mend\Mvc\View;

class LayoutTest extends \TestCase {
	public function testRender() {
		$culture = $this->createCulture();

		$layout = new Layout();

		$layout->setTitle( 'title' );
		$layout->setContent( 'content' );
		$layout->setCulture( $culture );

		$scriptFile = $this->createFile( 'test:///foo.phtml' );
		$output = $layout->render( $scriptFile );

		self::assertTrue( is_string( $output ) );
	}

	public function testRenderNoCulture() {
		$layout = new Layout();

		$layout->setTitle( 'title' );
		$layout->setContent( 'content' );

		$scriptFile = $this->createFile( 'test:///foo.phtml' );
		$output = $layout->render( $scriptFile );

		self::assertTrue( is_string( $output ) );
	}

	public function testRenderNoContent() {
		$layout = new Layout();

		$layout->setTitle( 'title' );

		$scriptFile = $this->createFile( 'test:///foo.phtml' );
		$output = $layout->render( $scriptFile );

		self::assertTrue( is_string( $output ) );
	}

	public function testRenderNoTitle() {
		$layout = new Layout();

		$scriptFile = $this->createFile( 'test:///foo.phtml' );
		$output = $layout->render( $scriptFile );

		self::assertTrue( is_string( $output ) );
	}

	private function createCulture() {
		return $this->getMock( '\Mend\I18n\Culture', array(), array(), '', false );
	}

	private function createFile( $location ) {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'exists', 'getName' ), array( $location ) );

		$file->expects( self::any() )
			->method( 'exists' )
			->will( self::returnValue( true ) );

		$file->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $location ) );

		return $file;
	}
}
