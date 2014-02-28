<?php
namespace Mend\Mvc;

class ViewRendererOptionsTest extends \TestCase {
	public function testAccessors() {
		$layoutTemplatePath = 'foo/bar';
		$layoutTemplateSuffix = '.phtml';
		$viewTemplatePath = 'bar/baz';
		$viewTemplateSuffix = '.lay';

		$options = new ViewRendererOptions();

		$options->setLayoutTemplatePath( $layoutTemplatePath );
		$options->setLayoutTemplateSuffix( $layoutTemplateSuffix );
		$options->setViewTemplatePath( $viewTemplatePath );
		$options->setViewTemplateSuffix( $viewTemplateSuffix );

		self::assertEquals( $layoutTemplatePath, $options->getLayoutTemplatePath() );
		self::assertEquals( $layoutTemplateSuffix, $options->getLayoutTemplateSuffix() );
		self::assertEquals( $viewTemplatePath, $options->getViewTemplatePath() );
		self::assertEquals( $viewTemplateSuffix, $options->getViewTemplateSuffix() );
	}
}