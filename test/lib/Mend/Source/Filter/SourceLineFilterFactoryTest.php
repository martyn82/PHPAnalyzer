<?php
namespace Mend\Source\Filter;

class SourceLineFilterFactoryTest extends \TestCase {
	public function testCreateByFileExtension() {
		$factory = new SourceLineFilterFactory();
		$filter = $factory->createByFileExtension( 'php' );
		self::assertTrue( $filter instanceof PHPSourceLineFilter );
	}
}