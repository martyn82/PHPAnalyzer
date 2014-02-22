<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Source\Code\Model\MethodArray;
use Mend\Parser\Node\PHPNode;
use Mend\Network\Web\Url;
use Mend\Source\Code\Model\Method;

class MethodPartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;

		$method = $this->getMock( '\Mend\Source\Code\Model\Method', array(), array(), '', false );
		$methods = new MethodArray();
		$methods[] = $method;

		$partition = new MethodPartition( $absolute, $relative, $methods );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $methods, $partition->getMethods() );

		$expectedArray = array(
			'absolute' => $absolute,
			'relative' => $relative,
			'methods' => array_map(
				function ( Method $method ) {
					return $method->toArray();
				},
				(array) $methods
			)
		);

		self::assertEquals( $expectedArray, $partition->toArray() );
	}

	public function testEmpty() {
		$empty = MethodPartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new MethodArray(), $empty->getMethods() );
	}
}