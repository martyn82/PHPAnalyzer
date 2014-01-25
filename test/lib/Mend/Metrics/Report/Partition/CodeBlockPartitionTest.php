<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Metrics\Extract\CodeBlockTable;

class CodeBlockPartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;
		$blocks = $this->getMock( '\Mend\Metrics\Extract\CodeBlockTable' );

		$partition = new CodeBlockPartition( $absolute, $relative, $blocks );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $blocks, $partition->getBlocks() );
	}

	public function testEmpty() {
		$empty = CodeBlockPartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new CodeBlockTable(), $empty->getBlocks() );
	}
}
