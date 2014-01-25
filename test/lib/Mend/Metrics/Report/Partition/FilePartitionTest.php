<?php
namespace Mend\Metrics\Report\Partition;

use Mend\IO\FileSystem\FileArray;

class FilePartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;
		$files = $this->getMock( '\Mend\IO\FileSystem\FileArray' );

		$partition = new FilePartition( $absolute, $relative, $files );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $files, $partition->getFiles() );
	}

	public function testEmpty() {
		$empty = FilePartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new FileArray(), $empty->getFiles() );
	}
}