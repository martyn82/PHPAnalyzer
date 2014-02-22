<?php
namespace Mend\Parser\Node;

class PHPNodeTest extends \TestCase {
	public function testAccessors() {
		$inner = $this->getMock( '\PHPParser_Node' );
		$inner->name = 'Foo node';
		$inner->setAttribute( 'startLine', 12 );
		$inner->setAttribute( 'endLine', 32 );

		$node = new PHPNode( $inner );

		self::assertEquals( $inner, $node->getInnerNode() );
		self::assertEquals( $inner->getAttribute( 'startLine' ), $node->getStartLine() );
		self::assertEquals( $inner->getAttribute( 'endLine' ), $node->getEndLine() );
		self::assertEquals( $inner->name, $node->getName() );
		self::assertEquals( '\\', $node->getPackageSeparator() );
	}
}