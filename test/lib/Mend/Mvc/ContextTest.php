<?php
namespace Mend\Mvc;

class ContextTest extends \TestCase {
	/**
	 * @dataProvider objectDataProvider
	 *
	 * @param string $contentType
	 * @param string $templateFileSuffix
	 * @param string $charset
	 */
	public function testAccessors( $contentType, $templateFileSuffix, $charset ) {
		$context = Context::create( $contentType, $templateFileSuffix, $charset );

		self::assertEquals( $contentType, $context->getContentType() );
		self::assertEquals( $templateFileSuffix, $context->getTemplateFileSuffix() );
		self::assertEquals( $charset, $context->getCharacterSet() );
	}

	public function objectDataProvider() {
		return array(
			array( 'text/plain', '.phtml', 'utf-8' ),
			array( null, null, null ),
			array( 'foo', 'bar', 'baz' )
		);
	}
}
