<?php
namespace Mend\Source\Extract;

use Mend\IO\FileSystem\File;
use Mend\IO\Stream\FileStreamReader;

class SourceFileExtractorTest extends \TestCase {
	private static $CODE_FRAGMENT_1 = <<<PHP
<?php
namespace Vendor\Foo\Bar;

class FooBar {
	/**
	 * Doc block
	 */
	public function __construct( \$one ) {
	}
}
PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
#!/usr/bin/php
<?php
exit( 0 );
PHP;

	public function testFilterCreation() {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getExtension' ), array( '/tmp/foo' ) );
		$file->expects( self::any() )->method( 'getExtension' )->will( self::returnValue( 'php' ) );

		$extractor = new SourceFileExtractor( $file );
		self::assertTrue( $extractor->getSourceLineFilter() instanceof \Mend\Source\Filter\PHPSourceLineFilter );
	}

	/**
	 * @dataProvider sourceProvider
	 *
	 * @param string $source
	 * @param array $lines
	 */
	public function testSourceLines( $source, array $lines ) {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getExtension' ), array( '/tmp/foo' ), '', false );
		$file->expects( self::any() )->method( 'getExtension' )->will( self::returnValue( 'php' ) );

		$extractor = $this->getMock(
			'\Mend\Source\Extract\SourceFileExtractor',
			array( 'getFileSource' ),
			array( $file )
		);

		$extractor->expects( self::any() )->method( 'getFileSource' )->will( self::returnValue( $source ) );

		self::assertEquals( $lines, $extractor->getSourceLines() );
	}

	/**
	 * Data provider with source code.
	 *
	 * @return array
	 */
	public function sourceProvider() {
		return array(
			array( self::$CODE_FRAGMENT_1, $this->indexSourceLines( self::$CODE_FRAGMENT_1 ) )
		);
	}

	/**
	 * Converts the given string into an array of lines as values and line numbers as keys.
	 *
	 * @param string $source
	 *
	 * @return array
	 */
	private function indexSourceLines( $source ) {
		$lines = explode( "\n", $source );
		$numbers = range( 1, count( $lines ) );
		return array_combine( $numbers, $lines );
	}
}