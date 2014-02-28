<?php
namespace Mend\I18n;

class CultureTest extends \TestCase {
	public function testAccessors() {
		$locale = 'foo';
		$readDir = 'bar';
		$charset = 'baz';

		$culture = new Culture( $locale, $readDir, $charset );

		self::assertEquals( $locale, $culture->getLocale() );
		self::assertEquals( $readDir, $culture->getReadingDirection() );
		self::assertEquals( $charset, $culture->getCharset() );
	}
}
