<?php
namespace Mend\Config;

class ArrayConfigReaderTest extends \TestCase {
	public function testAccessors() {
		$settings = array(
			'development' => array(
				'project.key' => 'Project Key'
			),
			'one' => 1
		);

		$reader = new ArrayConfigReader( $settings );
		self::assertTrue( $reader->entryExists( 'development:project.key' ) );
		self::assertFalse( $reader->entryExists( 'something:different' ) );

		$value = $reader->getValue( 'development:project.key' );
		self::assertEquals( 'Project Key', $value );

		$value = $reader->getValue( 'one' );
		self::assertTrue( $reader->entryExists( 'one' ) );
		self::assertEquals( 1, $value );
	}

	/**
	 * @expectedException \Mend\Config\ConfigurationException
	 */
	public function testNonExistentAccess() {
		$reader = new ArrayConfigReader( array() );
		$reader->getValue( 'foo' );

		self::fail( "Unexpected: Test should throw an exception." );
	}

	/**
	 * @expectedException \Mend\Config\ConfigurationException
	 */
	public function testNonExistentAccessComplex() {
		$reader = new ArrayConfigReader( array( 'foo' => array( 'bar' => '' ) ) );
		$reader->getValue( 'foo:baz' );

		self::fail( "Unexpected: Test should throw an exception." );
	}

	public function testReloadHasNoOp() {
		$settings = array(
			'foo' => 'bar'
		);

		$reader = new ArrayConfigReader( $settings );

		self::assertEquals( 'bar', $reader->getValue( 'foo' ) );
		$reader->reload();

		self::assertEquals( 'bar', $reader->getValue( 'foo' ) );
	}
}
