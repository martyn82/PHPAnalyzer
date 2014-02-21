<?php
namespace Mend\Logging;

class LogLevelTest extends \TestCase {
	public function testGetLevels() {
		$levels = LogLevel::getLevels();

		self::assertNotEmpty( $levels );
		self::assertTrue( is_array( $levels ) );
	}
}