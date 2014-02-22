<?php
namespace Mend\Metrics\Duplication;

use Mend\Network\Web\Url;

class CodeBlockTest extends \TestCase {
	public function testAccessors() {
		$url = Url::createFromString( 'file://' );
		$sourceUrl = $this->getMock( '\Mend\Source\Code\Location\SourceUrl', array(), array( $url ) );
		$sourceLines = array( 1 => 'foo', 2 => 'bar' );
		$index = mt_rand( 1, PHP_INT_MAX );

		$block = new CodeBlock( $sourceUrl, $sourceLines, $index );

		self::assertEquals( $sourceUrl, $block->getLocation() );
		self::assertEquals( $sourceLines, $block->getSourceLines() );
		self::assertEquals( $index, $block->getIndex() );
	}
}