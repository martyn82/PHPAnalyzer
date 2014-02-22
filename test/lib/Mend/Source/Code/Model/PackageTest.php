<?php
namespace Mend\Source\Code\Model;

use Mend\Network\Web\Url;

class PackageTest extends \TestCase {
	public function testAccessors() {
		$node = $this->getMock( '\Mend\Parser\Node\Node' );
		$url = Url::createFromString( 'file://' );
		$sourceUrl = $this->getMock( '\Mend\Source\Code\Location\SourceUrl', array(), array( $url ) );

		$package = new Package( $node, $sourceUrl );

		$classes = new ClassModelArray();
		$classes[] = $this->getMock( '\Mend\Source\Code\Model\ClassModel', array(), array(), '', false );
		$package->classes( $classes );

		self::assertEquals( $node->getName(), $package->getName() );
		self::assertEquals( $classes, $package->classes() );

		$expectedArray = array(
			'name' => $node->getName(),
			'location' => $sourceUrl->__toString(),
			'classes' => array_map(
				function ( ClassModel $class ) {
					return $class->toArray();
				},
				(array) $classes
			)
		);

		self::assertEquals( $expectedArray, $package->toArray() );
	}

	public function testDefaultPackageWithConstructor() {
		$package = new Package();

		self::assertFalse( $package->hasNode() );
		self::assertTrue( $package->isDefault() );
		self::assertEquals( Package::DEFAULT_PACKAGE_NAME, $package->getName() );

		$expectedArray = array(
			'name' => Package::DEFAULT_PACKAGE_NAME,
			'location' => null,
			'classes' => array()
		);

		self::assertEquals( $expectedArray, $package->toArray() );
	}

	public function testDefaultPackageWithFactoryMethod() {
		$package = Package::createDefault();

		self::assertFalse( $package->hasNode() );
		self::assertTrue( $package->isDefault() );
		self::assertEquals( Package::DEFAULT_PACKAGE_NAME, $package->getName() );

		$expectedArray = array(
			'name' => Package::DEFAULT_PACKAGE_NAME,
			'location' => null,
			'classes' => array()
		);

		self::assertEquals( $expectedArray, $package->toArray() );
	}
}