<?php
namespace Mend\Metrics\Duplication;

use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\Network\Web\Url;
use Mend\Source\Code\Location\Location;
use Mend\Source\Code\Location\SourceUrl;
use Mend\Source\Extract\SourceFileExtractor;
use Mend\IO\Stream\IsReadable;

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

	private static $CODE_FRAGMENT_3 = <<<PHP
<?php
function foo() {
	\$foo = 1;
	\$bar = \$foo++;
	\$baz = \$foo * \$bar;
}
PHP;

	private function getFileName() {
		return \FileSystem::PROTOCOL . ':///tmp/foo';
	}

	public function setUp() {
		\FileSystem::resetResults();
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	/**
	 * @dataProvider codeBlockProvider
	 *
	 * @param array $sourceLines
	 * @param CodeBlockArray $blocks
	 */
	public function testGetCodeBlocksFromFile( array $sourceLines, CodeBlockArray $blocks ) {
		$file = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->setMethods( array( 'getName' ) )
			->setConstructorArgs( array( $this->getFileName() ) )
			->getMock();

		$file->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $this->getFileName() ) );

		$extractor = $this->getMockBuilder( '\Mend\Metrics\Duplication\CodeBlockExtractor' )
			->setMethods( array( 'getFileSourceLines' ) )
			->getMock();

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
		$extractor = $this->getMockBuilder( '\Mend\Metrics\Duplication\CodeBlockExtractor' )
			->setMethods( array( 'getCodeBlocksFromFile' ) )
			->getMock();

		$files = new FileArray();

		$files[] = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->setConstructorArgs( array( $this->getFileName() ) )
			->getMock();

		$extractor->expects( self::exactly( count( $files ) ) )
			->method( 'getCodeBlocksFromFile' );

		$extractor->getCodeBlocks( $files );
	}

	/**
	 * @dataProvider sourceProvider
	 *
	 * @param string $source
	 */
	public function testGetFileSourceLines( $source ) {
		$file = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->setMethods( array( 'getExtension', 'getName' ) )
			->setConstructorArgs( array( $this->getFileName() ) )
			->getMock();

		$file->expects( self::any() )
			->method( 'getExtension' )
			->will( self::returnValue( 'php' ) );

		$file->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $this->getFileName() ) );

		IsReadable::$result = true;
		\FileSystem::setFReadResult( $source );

		$sourceExtractor = new SourceFileExtractor( $file );
		$filter = $sourceExtractor->getSourceLineFilter();

		$expectedLines = array_filter(
			$this->getLines( $source ),
			function ( $line ) use ( $filter ) {
				return $filter->isCode( $line );
			}
		);

		$extractor = new DummyCodeBlockExtractor();
		$sourceLines = $extractor->getFileSourceLines( $file );

		self::assertEquals( $expectedLines, $sourceLines );
	}

	public function sourceProvider() {
		return array(
			array( self::$CODE_FRAGMENT_1 ),
			array( self::$CODE_FRAGMENT_2 ),
			array( self::$CODE_FRAGMENT_3 )
		);
	}
}

class DummyCodeBlockExtractor extends CodeBlockExtractor {
	public function getFileSourceLines( File $file ) {
		return parent::getFileSourceLines( $file );
	}
}
