<?php
namespace Mend\Parser\Node;

class NodeFilterTest extends \TestCase {
	const PACKAGE_SEPARATOR = '\\';

	private function createNode( array $name, $withInnerNode = true ) {
		$node = $this->getMock(
			'\Mend\Parser\Node\Node',
			array( 'getName', 'getPackageSeparator', 'getStartLine', 'getEndLine', 'getInnerNode' )
		);

		if ( $withInnerNode ) {
			$innerNode = new \stdClass();
			$innerNode->class = new \stdClass();
			$innerNode->class->parts = $name;

			$node->expects( self::any() )
				->method( 'getInnerNode' )
				->will( self::returnValue( $innerNode ) );
		}

		$node->expects( self::any() )
			->method( 'getPackageSeparator' )
			->will( self::returnValue( self::PACKAGE_SEPARATOR ) );

		$node->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( end( $name ) ) );

		return $node;
	}

	public function testGetUnique() {
		$node1 = $this->createNode( array( 'foo', 'bar' ) );
		$node2 = $this->createNode( array( 'foo', 'baz' ) );
		$node3 = $this->createNode( array( 'foo', 'bar' ) );

		$nodes = array( $node1, $node2, $node3 );
		$expected = array( $node1, $node2 );

		$filter = new NodeFilter();
		$actual = $filter->getUnique( $nodes );

		self::assertEquals( $expected, $actual );
	}

	public function testGetClassName() {
		$node = $this->createNode( array( 'foo', 'bar' ) );

		$filter = new NodeFilter();
		$className = $filter->getClassName( $node );

		self::assertEquals( 'bar', $className );
	}

	/**
	 * @expectedException \Exception
	 */
	public function testGetClassNameNoFullyQualifiedName() {
		$node = $this->createNode( array(), false );

		$node->expects( self::any() )
			->method( 'getInnerNode' )
			->will( self::returnValue( new \stdClass() ) );

		$filter = new NodeFilter();
		$filter->getClassName( $node );

		self::fail( "Test should have triggered an exception." );
	}

	public function testGetFullyQualifiedName() {
		$node = $this->createNode( array( 'foo', 'bar' ) );

		$filter = new NodeFilter();
		$fqname = $filter->getFullyQualifiedName( $node );

		self::assertEquals( 'foo' . self::PACKAGE_SEPARATOR . 'bar', $fqname );
	}
}