<?php
namespace Mend\Mvc\Controller;

class ControllerLoaderTest extends \TestCase {
	/**
	 * @dataProvider mappingProvider
	 *
	 * @param array $mapping
	 */
	public function testGetControllerClassName( array $mapping ) {
		self::markTestIncomplete();
	}

	public function mappingProvider() {
		return array(
			array( array() )
		);
	}
}
