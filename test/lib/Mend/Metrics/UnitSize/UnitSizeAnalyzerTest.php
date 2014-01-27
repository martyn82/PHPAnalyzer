<?php
namespace Mend\Metrics\UnitSize;

require_once PARSER_BOOTSTRAP;

use Mend\IO\FileSystem\File;
use Mend\IO\Stream\FileStreamReader;
use Mend\Network\Web\Url;
use Mend\Parser\Node\PHPNode;
use Mend\Source\Code\Location\SourceUrl;
use Mend\Source\Code\Model\Method;
use Mend\Source\Extract\SourceFileExtractor;

class UnitSizeAnalyzerTest extends \TestCase {
	private static $CODE_FRAGMENT_1 = <<<PHP
<?php
namespace Vendor\Package;

class Foo extends Bar {
	/**
	 * Returns always true.
	 *
	 * @return boolean
	 */
	public function fooMethod() {
		return true;
	}
}
PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
<?php
function fooMethod() {
	if ( true ) {
	}
	else if ( false ) {
	}
	else {
	}

	while ( true ) {
	}

	foreach ( \$one ) {
	}
}
PHP;

	/**
	 * @dataProvider methodBodyProvider
	 *
	 * @param array $lines
	 * @param integer $startLine
	 * @param integer $endLine
	 * @param integer $size
	 * @param integer $category
	 */
	public function testUnitSizeAnalysis( array $lines, $startLine, $endLine, $size, $category ) {
		$analyzer = $this->getMock(
			'\Mend\Metrics\UnitSize\UnitSizeAnalyzer',
			array( 'getSourceLines' ),
			array()
		);
		$analyzer->expects( self::any() )->method( 'getSourceLines' )->will( self::returnValue( $lines ) );

		$node = $this->getMock( '\PHPParser_Node_Stmt_Function', array(), array( 'fooMethod' ) );
		$url = new SourceUrl( Url::createFromString( "file:///tmp/foo.php#({$startLine},0),({$endLine},10)" ) );
		$method = new Method( new PHPNode( $node ), $url );

		$result = $analyzer->calculateMethodSize( $method );

		self::assertEquals( $size, $result->getUnitSize() );
		self::assertEquals( $category, $result->getCategory() );
	}

	public function methodBodyProvider() {
		return array(
			array( $this->toLines( self::$CODE_FRAGMENT_1 ), 10, 12,  3, UnitSizeCategory::SIZE_SMALL ),
			array( $this->toLines( self::$CODE_FRAGMENT_2 ),  2, 15, 14, UnitSizeCategory::SIZE_MEDIUM )
		);
	}

	private function toLines( $source ) {
		$lines = explode( "\n", $source );
		$numbers = range( 1, count( $lines ) );
		return array_combine( $numbers, $lines );
	}
}
