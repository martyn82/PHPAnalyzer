<?php
namespace Mend\Logging;

class LoggerTest extends \TestCase {
	public function setUp() {
		Logger::clearHandlers();
	}

	public function testLogHandlerLevel() {
		$handler = $this->getMock( '\Mend\Logging\LogHandler' );
		$handler->expects( self::exactly( 2 ) )->method( 'log' );
		Logger::registerHandler( $handler );

		Logger::info( 'Lorem ipsum' );
		Logger::emergency( 'Lorem ipsum' );
	}

	public function testMultiHandlers() {
		$debugHandler = $this->getMock( '\Mend\Logging\LogHandler' );
		$debugHandler->expects( self::once() )->method( 'log' );

		$errorHandler = $this->getMock( '\Mend\Logging\LogHandler' );
		$errorHandler->expects( self::once() )->method( 'log' );

		Logger::registerHandler( $debugHandler, array( LogLevel::LEVEL_DEBUG ) );
		Logger::registerHandler( $errorHandler, array( LogLevel::LEVEL_ERROR ) );

		Logger::debug( 'Lorem debug ipsum' );
		Logger::error( 'Lorem error ipsum' );
	}

	/**
	 * @expectedException \Mend\Logging\LogException
	 */
	public function testNotRegistered() {
		Logger::alert( 'This is an alert' );
	}

	/**
	 * @expectedException \Mend\Logging\LogException
	 */
	public function testNotRegisteredSpecific() {
		$handler = $this->getMock( '\Mend\Logging\LogHandler' );
		$handler->expects( self::once() )->method( 'log' );

		Logger::registerHandler( $handler, array( LogLevel::LEVEL_ALERT ) );

		Logger::alert( 'This will be called' );
		Logger::emergency( 'This will throw exception' );

		self::fail( 'The test should not get here.' );
	}
}
