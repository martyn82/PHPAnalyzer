<?php
namespace Mend\Mvc\View;

class ViewRendererOptionsTest extends \TestCase {
	public function testAccessors() {
		$layoutTemplatePath = 'foo/bar';
		$layoutTemplateSuffix = '.phtml';
		$layoutDefaultTemplate = 'default';
		$viewTemplatePath = 'bar/baz';
		$viewTemplateSuffix = '.lay';

		$options = new ViewRendererOptions();

		$options->setLayoutTemplatePath( $layoutTemplatePath );
		$options->setLayoutTemplateSuffix( $layoutTemplateSuffix );
		$options->setLayoutDefaultTemplate( $layoutDefaultTemplate );
		$options->setViewTemplatePath( $viewTemplatePath );
		$options->setViewTemplateSuffix( $viewTemplateSuffix );

		self::assertEquals( $layoutTemplatePath, $options->getLayoutTemplatePath() );
		self::assertEquals( $layoutTemplateSuffix, $options->getLayoutTemplateSuffix() );
		self::assertEquals( $layoutDefaultTemplate, $options->getLayoutDefaultTemplate() );
		self::assertEquals( $viewTemplatePath, $options->getViewTemplatePath() );
		self::assertEquals( $viewTemplateSuffix, $options->getViewTemplateSuffix() );
	}
}