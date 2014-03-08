<?php
namespace Mend\Mvc\View;

class ViewOptionsTest extends \TestCase {
	public function testAccessors() {
		$options = new ViewOptions();
		$options->setLayoutEnabled( true );
		$options->setRendererEnabled( true );
		$options->setLayoutTemplatePath( 'layout/template' );
		$options->setLayoutTemplate( 'tpl' );
		$options->setViewTemplatePath( 'view/template' );

		self::assertEquals( true, $options->getLayoutEnabled() );
		self::assertEquals( true, $options->getRendererEnabled() );

		$options->setLayoutEnabled( false );
		$options->setRendererEnabled( false );

		self::assertEquals( false, $options->getLayoutEnabled() );
		self::assertEquals( false, $options->getRendererEnabled() );

		self::assertEquals( 'layout/template', $options->getLayoutTemplatePath() );
		self::assertEquals( 'tpl', $options->getLayoutTemplate() );
		self::assertEquals( 'view/template', $options->getViewTemplatePath() );
	}
}
