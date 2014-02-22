<?php
namespace Mend\Parser;

class ParserTest extends \TestCase {
	public function testParse() {
		$adapter = $this->getMock( '\Mend\Parser\Adapter' );
		$parser = new Parser( $adapter );

		self::assertNotNull( $parser );

		$adapter->expects( self::once() )
			->method( 'parse' );

		$parser->parse( 'foo' );
	}
}