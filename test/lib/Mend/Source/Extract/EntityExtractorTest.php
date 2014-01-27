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
	private static $CODE_FRAGMENT = <<<PHP
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

	public function testEntityExtracting() {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array(), array( '/tmp/foo' ) );

		$extractor = $this->getMock(
			'\Mend\Source\Extract\EntityExtractor',
			array( 'getFileSource' ),
			array( $file, new PHPParserAdapter(), new PHPNodeMapper() )
		);
		$extractor->expects( self::any() )
			->method( 'getFileSource' )
			->will( self::returnValue( self::$CODE_FRAGMENT ) );

		$classes = $extractor->getClasses();
		$packages = $extractor->getPackages();
		$methods = $extractor->getMethods();

		self::assertEquals( 2, count( $classes ) );
		self::assertEquals( 2, count( $packages ) );
		self::assertEquals( 4, count( $methods ) );
	}
}
