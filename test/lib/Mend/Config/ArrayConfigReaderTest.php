<?php
namespace Mend\Config;

class ArrayConfigReaderTest extends \TestCase {
	public function testAccessors() {
		$settings = array(
			'development' => array(
				'project.key' => 'Project Key'
			)
		);

		$reader = new ArrayConfigReader( $settings );
		self::assertTrue( $reader->entryExists( 'development:project.key' ) );
		self::assertFalse( $reader->entryExists( 'something:different' ) );

		$value = $reader->getValue( 'development:project.key' );
		self::assertEquals( 'Project Key', $value );
	}
}

