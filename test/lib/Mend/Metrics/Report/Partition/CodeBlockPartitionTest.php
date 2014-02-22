<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Metrics\Duplication\CodeBlock;
use Mend\Metrics\Duplication\CodeBlockTable;
use Mend\Network\Web\Url;

class CodeBlockPartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;

		$blocks = new CodeBlockTable();

		$url = Url::createFromString( 'file://' );
		$sourceUrl = $this->getMock( '\Mend\Source\Code\Location\SourceUrl', array(), array( $url ) );
		$blocks[] = new CodeBlock( $sourceUrl, array(), 1 );

		$partition = new CodeBlockPartition( $absolute, $relative, $blocks );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $blocks, $partition->getBlocks() );

		$aggregatedBlocks = array();
		foreach ( (array) $blocks as $hash => $bucket ) {
			$aggregatedBlocks[] = array_map(
				function ( CodeBlock $block ) {
					return array(
						'block' => $block->getSourceLines(),
						'location' => $block->getLocation()->__toString()
					);
				},
				$bucket
			);
		}

		$expectedArray = array(
			'absolute' => $absolute,
			'relative' => $relative,
			'blocks' => $aggregatedBlocks
		);

		self::assertEquals( $expectedArray, $partition->toArray() );
	}

	public function testEmpty() {
		$empty = CodeBlockPartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new CodeBlockTable(), $empty->getBlocks() );
	}
}
