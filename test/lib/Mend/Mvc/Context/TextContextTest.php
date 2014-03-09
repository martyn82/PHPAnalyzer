<?php
namespace Mend\Mvc\Context;

use Mend\I18n\CharacterSet;
use Mend\Network\MimeType;

class TextContextTest extends \TestCase {
	public function testAccessors() {
		$context = new TextContext();

		self::assertEquals( MimeType::TEXT, $context->getContentType() );
		self::assertEquals( '.txt', $context->getTemplateFileSuffix() );
		self::assertEquals( CharacterSet::UNICODE_UTF8, $context->getCharacterSet() );
	}
}
