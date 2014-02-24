<?php
namespace Mend\Parser\Node;

class NodeArrayTest extends \TestCase {
	public function testAdd() {
		$array = new NodeArray();
		$node = $this->getMock( '\Mend\Parser\Node\Node' );
		$array[] = $node;

		self::assertEquals( $node, $array[ 0 ] );
	}

	/**
	 * @expectedException \PHPUnit_Framework_Error
	 */
	public function testTypeCheck() {
		$array = new NodeArray();
		$noNode = new \stdClass();
		$array[] = $noNode;

		self::fail( "A type error was expected." );
	}
}