<?php
namespace Mend\Source\Extract;

use Mend\IO\FileSystem\File;
use Mend\Parser\Adapter;
use Mend\Parser\Adapter\PHPParserAdapter;
use Mend\Parser\Node\Node;
use Mend\Parser\Node\PHPNodeMapper;
use Mend\Parser\Node\NodeMapper;
use Mend\Parser\Node\NodeType;
use Mend\Parser\Parser;
use Mend\Source\Code\ModelTraverser;
use Mend\Source\Code\ModelVisitor;

class EntityExtractorTest extends \TestCase {
	private static $CODE_FRAGMENT_1 = <<<PHP
<?php
namespace Vendor\Package {

	use Vendor\Package\Bar;

	class Foo implements Bar {
		public function foo() {
		}

		public function bar() {
		}

		public function baz() {
		}
	}
}

namespace Vendor\Package\Bar {
	interface Bar {
		function baz();
	}
}
PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
<?php
class Foo {
	public function foo() {}
}
PHP;

	public function testEntityExtracting() {
		$file = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->setConstructorArgs( array( '/tmp/foo' ) )
			->getMock();

		$extractor = $this->getMock(
			'\Mend\Source\Extract\EntityExtractor',
			array( 'getFileSource' ),
			array( $file, new PHPParserAdapter(), new PHPNodeMapper() )
		);

		$extractor->expects( self::any() )
			->method( 'getFileSource' )
			->will( self::returnValue( self::$CODE_FRAGMENT_1 ) );

		$classes = $extractor->getClasses();
		$packages = $extractor->getPackages();
		$methods = $extractor->getMethods();

		self::assertEquals( 2, count( $classes ) );
		self::assertEquals( 2, count( $packages ) );
		self::assertEquals( 4, count( $methods ) );

		$package = reset( $packages );
		$classes = $extractor->getClasses( $package );
		self::assertEquals( 1, count( $classes ) );

		$class = reset( $classes );
		$methods = $extractor->getMethods( $class );
		self::assertEquals( 3, count( $methods ) );
	}

	public function testEntityExtractingNoPackages() {
		$file = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->setConstructorArgs( array( '/tmp/foo' ) )
			->getMock();

		$extractor = $this->getMock(
			'\Mend\Source\Extract\EntityExtractor',
			array( 'getFileSource' ),
			array( $file, new PHPParserAdapter(), new PHPNodeMapper() )
		);

		$extractor->expects( self::any() )
			->method( 'getFileSource' )
			->will( self::returnValue( self::$CODE_FRAGMENT_2 ) );

		$packages = $extractor->getPackages();
		self::assertEquals( 1, count( $packages ) );

		$package = reset( $packages );
		$classes = $extractor->getClasses( $package );
		self::assertEquals( 1, count( $classes ) );

		$class = reset( $classes );
		$methods = $extractor->getMethods( $class );
		self::assertEquals( 1, count( $methods ) );
	}

	public function testGetFileSource() {
		$file = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->disableOriginalConstructor()
			->getMock();

		$extractor = $this->getMock(
			'\Mend\Source\Extract\EntityExtractor',
			array( 'getSourceExtractor' ),
			array( $file, new PHPParserAdapter(), new PHPNodeMapper() )
		);

		$sourceExtractor = $this->getMockBuilder( '\Mend\Source\Extract\SourceFileExtractor' )
			->setConstructorArgs( array( $file ) )
			->disableOriginalConstructor()
			->getMock();

		$extractor->expects( self::any() )
			->method( 'getSourceExtractor' )
			->will( self::returnValue( $sourceExtractor ) );

		self::assertNotNull( $extractor->getAST() );
	}
}
