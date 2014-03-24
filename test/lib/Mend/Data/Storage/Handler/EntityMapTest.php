<?php
namespace Mend\Data\Storage\Handler;

class EntityMapTest extends \TestCase {
	public function testAdd() {
		$map = new EntityMap();

		self::assertEquals( 0, $map->getSize() );

		$directory = $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->disableOriginalConstructor()
			->getMock();

		$map->set( 'foo', $directory );
	}

	/**
	 * @expectedException \PHPUnit_Framework_Error
	 */
	public function testAddInvalidType() {
		$map = new EntityMap();
		$map->set( 'foo', new \stdClass() );

		self::fail( "Test should have triggered an exception." );
	}
}
