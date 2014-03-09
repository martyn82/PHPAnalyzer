<?php
namespace Mend\Mvc\Context;

use Mend\I18n\CharacterSet;
use Mend\Network\MimeType;

class JsonContextTest extends \TestCase {
	public function testAccessors() {
		$context = new JsonContext();

		self::assertEquals( MimeType::JSON, $context->getContentType() );
		self::assertEquals( '.json', $context->getTemplateFileSuffix() );
		self::assertEquals( CharacterSet::UNICODE_UTF8, $context->getCharacterSet() );
	}
}
