<?php
namespace Mend\Metrics\Source;

class SourceLineFilterFactoryTest extends \TestCase {
	public function testCreateByFileExtension() {
		$factory = new SourceLineFilterFactory();
		$filter = $factory->createByFileExtension( 'php' );
		self::assertTrue( $filter instanceof PHPSourceLineFilter );
	}
}