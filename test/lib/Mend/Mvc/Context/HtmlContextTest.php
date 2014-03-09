<?php
namespace Mend\Mvc\Context;

use Mend\I18n\CharacterSet;
use Mend\Network\MimeType;

class HtmlContextTest extends \TestCase {
	public function testAccessors() {
		$context = new HtmlContext();

		self::assertEquals( MimeType::HTML, $context->getContentType() );
		self::assertEquals( '.phtml', $context->getTemplateFileSuffix() );
		self::assertEquals( CharacterSet::UNICODE_UTF8, $context->getCharacterSet() );
	}
}
