<?php
namespace Mend\Source\Code\Model;

use Mend\Network\Web\Url;
use Mend\Parser\Adapter\PHPParserAdapter;
use Mend\Parser\Parser;
use Mend\Parser\Node\PHPNode;
use Mend\Source\Code\Location\SourceUrl;

class ClassModelTest extends \TestCase {
	private static $CODE_FRAGMENT = <<<PHP
<?php
namespace Vendor;

class Foo {
	public function bar() {
	}
}
PHP;

	public function testAccessors() {
		$ast = $this->getAST( self::$CODE_FRAGMENT );
		$classNode = new PHPNode( $ast[ 0 ]->stmts[ 0 ] );
		$classModel = new ClassModel( $classNode, new SourceUrl( Url::createFromString( 'file://' ) ) );

		self::assertEquals( $classNode, $classModel->getNode() );
		self::assertEquals( 'Foo', $classModel->getName() );
	}

	/**
	 * Parses given source and returns the AST.
	 *
	 * @param string $source
	 *
	 * @return array
	 */
	private function getAST( $source ) {
		$adapter = new PHPParserAdapter();
		$parser = new Parser( $adapter );
		return $parser->parse( $source );
	}
}
