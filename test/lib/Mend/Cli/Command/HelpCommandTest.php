<?php
namespace Mend\Cli\Command;

use Mend\Cli\Status;
class HelpCommandTest extends \TestCase {
	/**
	 * @dataProvider argumentProvider
	 *
	 * @param string $currentScriptName
	 * @param string $defaultMemoryLimit
	 */
	public function testRun( $currentScriptName, $defaultMemoryLimit ) {
		$help = new HelpCommand( $currentScriptName, $defaultMemoryLimit );
		$result = $help->run();

		self::assertInstanceOf( '\Mend\Cli\CommandResult', $result );
		self::assertFalse( $result->isError() );
		self::assertEquals( Status::STATUS_OK, $result->getStatus() );
		self::assertNotNull( $result->getMessage() );
		self::assertTrue( is_string( $result->getMessage() ) );
	}

	public function argumentProvider() {
		return array(
			array( 'script_name', '1M' )
		);
	}
}