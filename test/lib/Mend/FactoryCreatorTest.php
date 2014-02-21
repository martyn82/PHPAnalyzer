<?php
namespace Mend;

class FactoryCreatorTest extends \TestCase {
	/**
	 * @dataProvider extensionsProvider
	 *
	 * @param string $extension
	 */
	public function testCreateFactoryByFileExtension( $extension ) {
		$creator = new FactoryCreator();
		$factory = $creator->createFactoryByFileExtension( $extension );

		self::assertNotNull( $factory );
		self::assertInstanceOf( '\Mend\PHPFactory', $factory );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testCreateFactoryNonExistent() {
		$extension = 'foo';
		$creator = new FactoryCreator();
		$creator->createFactoryByFileExtension( $extension );

		self::fail( "Unexpected: factory successfully created." );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCreateFactoryInvalidArgument() {
		$creator = new FactoryCreator();
		$creator->createFactoryByFileExtension( 1 );

		self::fail( "Unexpected: factory successfully created." );
	}

	/**
	 * @return array
	 */
	public function extensionsProvider() {
		return array(
			array( FactoryCreator::EXTENSION_PHP )
		);
	}
}