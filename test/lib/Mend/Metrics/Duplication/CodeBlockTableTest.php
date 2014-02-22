<?php
namespace Mend\Metrics\Duplication;

class CodeBlockTableTest extends \TestCase {
	public function testAdd() {
		$table = new CodeBlockTable();
		$block = $this->getMock( '\Mend\Metrics\Duplication\CodeBlock', array(), array(), '', false );
		$table[] = $block;

		self::assertEquals( array( $block ), $table[ 0 ] );
	}

	/**
	 * @expectedException \PHPUnit_Framework_Error
	 */
	public function testTypeCheck() {
		$table = new CodeBlockTable();
		$nonBlock = new \stdClass();
		$table[] = $nonBlock;

		self::fail( "Expected a type error." );
	}
}