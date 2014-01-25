<?php
namespace Mend\Metrics\Model\Code;

use Mend\Network\Web\Url;

class SourceUrlTest extends \TestCase {
	/**
	 * @dataProvider urlProvider
	 *
	 * @param string $urlString
	 * @param string $scheme
	 * @param string $path
	 * @param integer $startLine
	 * @param integer $startColumn
	 * @param integer $endLine
	 * @param integer $endColumn
	 */
	public function testUrl( $urlString, $scheme, $path, $startLine, $startColumn, $endLine, $endColumn ) {
		$url = Url::createFromString( $urlString );
		$sourceUrl = new SourceUrl( $url );

		self::assertEquals( $urlString, (string) $sourceUrl );
		self::assertEquals( $scheme, $sourceUrl->getScheme() );
		self::assertEquals( $path, $sourceUrl->getPath() );
		self::assertEquals( $path, $sourceUrl->getFilename() );
		self::assertEquals( $startLine, $sourceUrl->getStart()->getLine() );
		self::assertEquals( $startColumn, $sourceUrl->getStart()->getColumn() );
		self::assertEquals( $endLine, $sourceUrl->getEnd()->getLine() );
		self::assertEquals( $endColumn, $sourceUrl->getEnd()->getColumn() );
	}

	public function urlProvider() {
		return array(
			array( 'file:///tmp/foo#(1,0),(1,10)', 'file', '/tmp/foo', 1, 0, 1, 10 ),
			array( 'file:///tmp/foo', 'file', '/tmp/foo', null, null, null, null )
		);
	}
}