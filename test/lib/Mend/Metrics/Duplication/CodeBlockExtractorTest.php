<?php
namespace Mend\Metrics\Duplication;

require_once realpath( __DIR__ . "/../../IO/Stream" ) . "/FileStreamTest.php";

use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\Network\Web\Url;
use Mend\Source\Code\Location\Location;
use Mend\Source\Code\Location\SourceUrl;
use Mend\IO\Stream\FileStreamTest;
use Mend\Source\Extract\SourceFileExtractor;

class CodeBlockExtractorTest extends FileStreamTest {
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

	private static $CODE_FRAGMENT_3 = <<<PHP
<?php
function foo() {
	\$foo = 1;
	\$bar = \$foo++;
	\$baz = \$foo * \$bar;
}
PHP;

	private function getFileName() {
		return '/tmp/foo';
	}

	/**
	 * @dataProvider codeBlockProvider
	 *
	 * @param array $sourceLines
	 * @param CodeBlockArray $blocks
	 */
	public function testGetCodeBlocksFromFile( array $sourceLines, CodeBlockArray $blocks ) {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getName' ), array( $this->getFileName() ) );
		$file->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $this->getFileName() ) );

		$extractor = $this->getMock( '\Mend\Metrics\Duplication\CodeBlockExtractor', array( 'getFileSourceLines' ) );
		$extractor->expects( self::any() )
			->method( 'getFileSourceLines' )
			->will( self::returnValue( $sourceLines ) );

		$actual = $extractor->getCodeBlocksFromFile( $file );

		self::assertEquals( count( $blocks ), count( $actual ) );
		self::assertEquals( $blocks, $actual );
	}

	/**
	 * @return array of array( sourceLines, blocks )
	 */
	public function codeBlockProvider() {
		return array(
			array( $this->getLines( self::$CODE_FRAGMENT_1 ), $this->getBlocks( self::$CODE_FRAGMENT_1 ) ),
			array( $this->getLines( self::$CODE_FRAGMENT_2 ), $this->getBlocks( self::$CODE_FRAGMENT_2 ) ),
			array( $this->getLines( self::$CODE_FRAGMENT_3 ), $this->getBlocks( self::$CODE_FRAGMENT_3 ) )
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
		$fileName = $this->getFileName();

		$blocks = new CodeBlockArray();

		for ( $index = 0; $length >= ( $index + $blockSize ); $index++ ) {
			$lineNumbers = array_slice( array_keys( $lines ), $index, $blockSize );
			$sourceLines = array_slice( $lines, $index, $blockSize );

			$url = Url::createFromString(
				"file://{$fileName}#" . sprintf(
					"(%d,0),(%d,0)",
					reset( $lineNumbers ),
					end( $lineNumbers )
				)
			);

			$location = new SourceUrl( $url );
			$blocks[] = new CodeBlock( $location, array_combine( $lineNumbers, $sourceLines ), $index );
		}

		return $blocks;
	}

	public function testCreateCodeBlocks() {
		$lines = array(
			 1 => '<?php',
			 2 => 'function foo() {',
			 3 => '    $foo = 12;',
			 4 => '    $bar = $foo * 2;',
			 5 => '    $baz = $foo + $bar;',
			 6 => '}',
			 7 => '',
			 8 => 'function bar() {',
			 9 => '    $foo = 21;',
			10 => '}'
		);
		$blocksCount = 5; // 1: 1-6, 2: 2-7, 3: 3-8, 4: 4-9, 5: 5-10

		$extractor = new CodeBlockExtractor();
		$blocks = $extractor->createCodeBlocks( $lines, $this->getFileName() );

		self::assertEquals( $blocksCount, count( $blocks ) );

		$index = 0;
		foreach ( $blocks as $block ) {
			/* @var $block CodeBlock */
			self::assertEquals(
				array_slice( $lines, $index, CodeBlock::DEFAULT_SIZE, true ),
				$block->getSourceLines()
			);
			self::assertEquals( $index, $block->getIndex() );
			$index++;
		}
	}

	public function testGetCodeBlocks() {
		$extractor = $this->getMock( '\Mend\Metrics\Duplication\CodeBlockExtractor', array( 'getCodeBlocksFromFile' ) );

		$files = new FileArray();
		$files[] = $this->getMock( '\Mend\IO\FileSystem\File', array(), array( $this->getFileName() ) );

		$extractor->expects( self::exactly( count( $files ) ) )
			->method( 'getCodeBlocksFromFile' );

		$extractor->getCodeBlocks( $files );
	}

	public function testGetFileSourceLines() {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getExtension' ), array( $this->getFileName() ) );
		$file->expects( self::any() )
			->method( 'getExtension' )
			->will( self::returnValue( 'php' ) );

		FileStreamTest::$fopenResult = true;
		FileStreamTest::$freadResult = self::$CODE_FRAGMENT_1;
		FileStreamTest::$isReadableResult = true;
		FileStreamTest::$isResourceResult = true;

		$sourceExtractor = new SourceFileExtractor( $file );
		$filter = $sourceExtractor->getSourceLineFilter();

		$expectedLines = array_filter(
			$this->getLines( self::$CODE_FRAGMENT_1 ),
			function ( $line ) use ( $filter ) {
				return $filter->isCode( $line );
			}
		);

		$extractor = new DummyCodeBlockExtractor();
		$sourceLines = $extractor->getFileSourceLines( $file );

		self::assertEquals( $expectedLines, $sourceLines );
	}
}

class DummyCodeBlockExtractor extends CodeBlockExtractor {
	public function getFileSourceLines( File $file ) {
		return parent::getFileSourceLines( $file );
	}
}
