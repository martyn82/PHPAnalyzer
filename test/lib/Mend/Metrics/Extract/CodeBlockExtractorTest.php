<?php
namespace Mend\Metrics\Extract;

use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\Metrics\Model\Code\Location;
use Mend\Metrics\Model\Code\SourceUrl;
use Mend\Network\Web\Url;

class CodeBlockExtractorTest extends \TestCase {
	private static $BLOCK_SIZE = 6;

	private static $CODE_FRAGMENT_1 = <<<PHP
<?php
namespace Vendor\Package;

class Foo extends Bar {
	public function __construct() {
	}
}
PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
<?php
namespace Vendor\Package;

class Foo extends Bar {
	/**
	 * Constructor
	 */
	public function __construct() {
		\$this->foo = null;
		// this should not occur in the code block
	}

	public function compute() {
		while ( true ) {
			// this is a comment
			\$index++;
		}
	}
}

PHP;

	/**
	 * @dataProvider codeBlockProvider
	 *
	 * @param array $lines
	 * @param CodeBlockArray $blocks
	 */
	public function testCodeBlock( array $lines, CodeBlockArray $blocks ) {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getExtension' ), array( '/tmp/foo.php' ) );
		$file->expects( self::any() )->method( 'getExtension' )->will( self::returnValue( 'php' ) );

		$extractor = $this->getMock( '\Mend\Metrics\Extract\CodeBlockExtractor', array( 'getFileSourceLines' ) );
		$extractor->expects( self::any() )->method( 'getFileSourceLines' )->will( self::returnValue( $lines ) );
		$codeBlocks = $extractor->getCodeBlocksFromFile( $file );

		self::assertEquals( $blocks, $codeBlocks );
	}

	/**
	 * @return array of array( sourceLines, blocks )
	 */
	public function codeBlockProvider() {
		return array(
			array( $this->getLines( self::$CODE_FRAGMENT_1 ), $this->getBlocks( self::$CODE_FRAGMENT_1 ) ),
			array( $this->getLines( self::$CODE_FRAGMENT_2 ), $this->getBlocks( self::$CODE_FRAGMENT_2 ) )
		);
	}

	/**
	 * Retrieves source lines.
	 *
	 * @param string $source
	 *
	 * @return array
	 */
	private function getLines( $source ) {
		$lines = explode( "\n", $source );
		$numbers = range( 1, count( $lines ) );
		return array_combine( $numbers, $lines );
	}

	/**
	 * Creates code blocks from given source.
	 *
	 * @param string $source
	 *
	 * @return CodeBlockArray
	 */
	private function getBlocks( $source ) {
		$lines = $this->getLines( $source );
		$blockSize = self::$BLOCK_SIZE;
		$length = count( $lines );

		$blocks = new CodeBlockArray();

		for ( $index = 0; $length >= ( $index + $blockSize ); $index++ ) {
			$lineNumbers = array_slice( array_keys( $lines ), $index, $blockSize );
			$sourceLines = array_slice( $lines, $index, $blockSize );

			$url = Url::createFromString(
				'file:///tmp/foo.php#' . sprintf( "(%d,0),(%d,0)", reset( $lineNumbers ), end( $lineNumbers ) )
			);
			$location = new SourceUrl( $url );
			$blocks[] = new CodeBlock( $location, array_combine( $lineNumbers, $sourceLines ), $index );
		}

		return $blocks;
	}
}
