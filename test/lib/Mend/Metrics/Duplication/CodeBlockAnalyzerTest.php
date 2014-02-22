<?php
namespace Mend\Metrics\Duplication;

class CodeBlockAnalyzerTest extends \TestCase {
	private static $CODE_FRAGMENT_1 = <<<PHP
<?php
namespace Vendor\Package;

class Foo {
	public function __construct() {
	}
}
PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
<?php
namespace Vendor\Package;

class Foo {
	public function doSomething() {
		\$x = 10;
		for ( \$i = 0; \$i < 100; \$i++ ) {
			while ( true ) {
				\$x *= \$i;
			}
		}

		\$x = 10;
		for ( \$i = 0; \$i < 100; \$i++ ) {
			while ( true ) {
				\$x *= \$i;
			}
		}
	}
}
PHP;

	private static $CODE_FRAGMENT_3 = <<<PHP
<?php
namespace Vendor\Package;

class Foo {
	public function doSomething() {
		\$x = 10;
		for ( \$i = 0; \$i < 100; \$i++ ) {
			while ( true ) {
				\$x *= \$i;
			}
		}
	}

	public function doSomething2() {
		\$x = 10;
		for ( \$i = 0; \$i < 100; \$i++ ) {
			while ( true ) {
				\$x *= \$i;
			}
		}
	}

	public function doSomething3() {
		\$x = 10;
		for ( \$i = 0; \$i < 100; \$i++ ) {
			while ( true ) {
				\$x *= \$i;
			}
		}
	}
}
PHP;
	/**
	 * @dataProvider sourceProvider
	 *
	 * @param string $source
	 * @param integer $duplicateBlockCount
	 * @param integer $duplicateLineCount
	 */
	public function testDuplicates( $source, $duplicateBlockCount, $duplicateLineCount ) {
		$lines = $this->getLines( $source );

		$extractor = new CodeBlockExtractor();
		$blocks = $extractor->createCodeBlocks( $lines );

		$analyzer = new CodeBlockAnalyzer();
		$dupes = $analyzer->findDuplicates( $blocks );
		$dupeArray = (array) $dupes;

		if ( empty( $dupeArray ) ) {
			$firstDupe = array();
		}
		else {
			$firstDupe = reset( $dupeArray );
		}

		self::assertEquals( $duplicateBlockCount, count( $firstDupe ) );

		$lineCount = $analyzer->getDuplicateLines( $dupes );
		self::assertEquals( $duplicateLineCount, $lineCount );
	}

	public function sourceProvider() {
		return array(
			array( self::$CODE_FRAGMENT_1,  0,  0 ),
			array( self::$CODE_FRAGMENT_2,  2, 12 ),
			array( self::$CODE_FRAGMENT_3,  3, 23 )
		);
	}

	private function getLines( $source ) {
		$lines = explode( "\n", $source );
		$numbers = range( 1, count( $lines ) );
		return array_combine( $numbers, $lines );
	}

	public function testGetHash() {
		$lines = array(
			1 => 'foo',
			2 => 'bar'
		);
		$expectedHash = "foo\nbar";

		$analyzer = new CodeBlockAnalyzer();
		self::assertEquals( $expectedHash, $analyzer->getHash( $lines ) );
	}
}
