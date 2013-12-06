<?php
namespace Parser;

class ParserTest extends \TestCase {
	public function testParseCall() {
		$adapter = $this->getMock(
			'Parser\PHPParserAdapter',
			array( 'parse' ),
			array(),
			'',
			false
		);

		$adapter->expects( $this->once() )
			->method( 'parse' );

		$parser = new Parser( $adapter );
		$parser->parse( '' );
	}
}
