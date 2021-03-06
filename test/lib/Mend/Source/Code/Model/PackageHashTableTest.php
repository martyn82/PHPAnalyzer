<?php
namespace Mend\Source\Code\Model;

class PackageHashTableTest extends \TestCase {
	public function testAdd() {
		$table = new PackageHashTable();
		$package = $this->getMock( '\Mend\Source\Code\Model\Package' );
		$table[] = $package;

		self::assertEquals( array( $package ), $table[ 0 ] );
	}

	/**
	 * @expectedException \PHPUnit_Framework_Error
	 */
	public function testTypeCheck() {
		$table = new PackageHashTable();
		$method = $this->getMockBuilder( '\Mend\Source\Code\Model\Method' )
			->disableOriginalConstructor()
			->getMock();

		$table[] = $method;

		self::fail( "Expected a type error." );
	}
}