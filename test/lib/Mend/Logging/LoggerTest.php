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

	public function testMultiHandlersDifferentLevel() {
		$debugHandler = $this->getMock( '\Mend\Logging\LogHandler' );
		$debugHandler->expects( self::once() )->method( 'log' );

		$errorHandler = $this->getMock( '\Mend\Logging\LogHandler' );
		$errorHandler->expects( self::once() )->method( 'log' );

		Logger::registerHandler( $debugHandler, array( LogLevel::LEVEL_DEBUG ) );
		Logger::registerHandler( $errorHandler, array( LogLevel::LEVEL_ERROR ) );

		Logger::debug( 'Lorem debug ipsum' );
		Logger::error( 'Lorem error ipsum' );
	}

	public function testMultiHandlersSameLevel() {
		$handler1 = $this->getMock( '\Mend\Logging\LogHandler' );
		$handler2 = $this->getMock( '\Mend\Logging\LogHandler' );

		$handler1->expects( self::once() )->method( 'log' );
		$handler2->expects( self::once() )->method( 'log' );

		Logger::registerHandler( $handler1, array( LogLevel::LEVEL_CRITICAL ) );
		Logger::registerHandler( $handler2, array( LogLevel::LEVEL_CRITICAL ) );

		Logger::critical( 'This calls both handlers.' );
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

	/**
	 * @dataProvider loglevelProvider
	 *
	 * @param integer $level
	 * @param string $functionName
	 */
	public function testCallLogOnAllLevels( $level, $functionName ) {
		$handler = $this->getMock( '\Mend\Logging\LogHandler' );
		$handler->expects( self::once() )->method( 'log' );

		Logger::registerHandler( $handler, array( $level ) );
		Logger::$functionName( 'testMessage' );
	}

	/**
	 * @return array
	 */
	public function loglevelProvider() {
		return array(
			array( LogLevel::LEVEL_DEBUG, 'debug' ),
			array( LogLevel::LEVEL_INFO, 'info' ),
			array( LogLevel::LEVEL_NOTICE, 'notice' ),
			array( LogLevel::LEVEL_WARNING, 'warning' ),
			array( LogLevel::LEVEL_ERROR, 'error' ),
			array( LogLevel::LEVEL_CRITICAL, 'critical' ),
			array( LogLevel::LEVEL_ALERT, 'alert' ),
			array( LogLevel::LEVEL_EMERGENCY, 'emergency' )
		);
	}

	/**
	 * @expectedException \Mend\Logging\LogException
	 */
	public function testRegisterUnknownLogLevel() {
		$level = -100; // non-existent log level
		$handler = $this->getMock( '\Mend\Logging\LogHandler' );
		Logger::registerHandler( $handler, array( $level ) );
		self::fail( "Test should have triggered an exception." );
	}
}
