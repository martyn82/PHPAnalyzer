<?php
namespace Mend\Cli;

class CommandResultTest extends \TestCase {
	/**
	 * @dataProvider resultProvider
	 *
	 * @param string $message
	 * @param integer $status
	 * @param boolean $isError
	 */
	public function testAccessors( $message, $status, $isError ) {
		$result = new CommandResult( $message, $status );

		self::assertEquals( $message, $result->getMessage() );
		self::assertEquals( $status, $result->getStatus() );
		self::assertEquals( $isError, $result->isError() );
	}

	public function resultProvider() {
		return array(
			array( 'foo message', Status::STATUS_OK, false ),
			array( 'foo message', Status::STATUS_ERROR_GENERAL, true )
		);
	}
}