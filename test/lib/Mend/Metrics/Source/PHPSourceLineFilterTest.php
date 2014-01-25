<?php
namespace Mend\Metrics\Source;

class PHPSourceLineFilterTest extends \TestCase {
	/**
	 * @dataProvider sourceLineProvider
	 *
	 * @param string $source
	 * @param boolean $isCode
	 * @param boolean $isBlank
	 * @param boolean $isComment
	 * @param boolean $isWhitespace
	 */
	public function testFilter( $source, $isCode, $isBlank, $isComment, $isWhitespace ) {
		$filter = new PHPSourceLineFilter();

		self::assertEquals( $isCode, $filter->isCode( $source ) );
		self::assertEquals( $isBlank, $filter->isBlank( $source ) );
		self::assertEquals( $isComment, $filter->isComment( $source ) );
		self::assertEquals( $isWhitespace, $filter->isWhitespace( $source ) );
	}

	public function testInsideComment() {
		$sourceLines = array(
			'this is code;' => false,
			'/* this is comment start' => true,
			'this is inside comment' => true,
			'this is comment end */' => true,
			'this is code again;' => false
		);

		$filter = new PHPSourceLineFilter();
		foreach ( $sourceLines as $line => $isComment ) {
			self::assertEquals( $isComment, $filter->isComment( $line ) );
		}
	}

	/**
	 * @return array of array( sourceLine, isCode, isBlank, isComment, isWhitespace )
	 */
	public function sourceLineProvider() {
		return array(
			array( '<?php', true, false, false, false ),
			array( "\t", false, false, false, true ),
			array( '', false, true, false, true ),
			array( '$data = array();', true, false, false, false ),
			array( '// this is a comment;', false, false, true, false ),
			array( '/* this is a comment */', false, false, true, false ),
			array( '/* this is a start of comment', false, false, true, false )
		);
	}
}
